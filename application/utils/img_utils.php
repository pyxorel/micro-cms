<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Вспомогательные методы для работы с изображениями
 */
class ImgUtils
{

    /**
     * Загрузка изображения с возможностью создания миниатюр (миниатюры имеют тоже имя что и файл,
     * распологаются каждая в папке согласно ее размеру)
     * @param $t - $this
     * @param array $tmbSizes - массив с необходимыми размерами миниатюр ( array('100_150', '160_100') )
     * @param string $path - путь к корневой директории, где будут распологаться изображения
     * @param string $mime - возможные mime типы файлов
     * @param string $nameField - название поля в форме с файлом
     * @return string - сообщение об ошибке , array('result'=>TRUE, 'file'=>название вновь созданного файла)
     */
    public static function uploadImg($t, array $tmbSizes, $path, $crop = false, $crop_size = '', $mime = 'jpg|png', $nameField = 'file', $quality = 60)
    {
        $t->load->helper('string');

        $newFile = random_string('unique');

        $config['upload_path'] = $path;
        $config['allowed_types'] = $mime;
        $config['file_name'] = $newFile;

        $t->load->library('upload', $config);
        if ($t->upload->do_upload($nameField) === FALSE) {
            return $t->upload->display_errors();
        }

        $data = $t->upload->data();

        $t->load->library('image_lib');
        $config['image_library'] = 'gd2';
        $source_file = $path . $newFile . $data['file_ext'];
        $config['source_image'] = $source_file;
        $config['maintain_ratio'] = TRUE;
        $config['quality'] = $quality . '%';//качество изображения

        //величина превышения пропорции
        $excess_proportion = 50;

        $crop_file = $path . $newFile . '___' . $data['file_ext'];
        $config['new_image'] = $crop_file;

        //ширина больше высоты
        if ($crop === TRUE && ($data['image_width'] - $data['image_height']) > $excess_proportion) {
            $config['create_thumb'] = FALSE;
            $config['maintain_ratio'] = FALSE;

            $min = $data['image_width'] - $data['image_height'];
            $config['x_axis'] = $min / 2;
            $config['y_axis'] = 0;
            $config['width'] = $data['image_height'];
            $config['height'] = $data['image_height'];
            $t->image_lib->initialize($config);
            $t->image_lib->crop();

            //высота больше ширины
        } else if ($crop === TRUE && ($data['image_height'] - $data['image_width']) > $excess_proportion) {

            $config['create_thumb'] = FALSE;
            $config['maintain_ratio'] = FALSE;

            $min = $data['image_height'] - $data['image_width'];
            $config['x_axis'] = 0;
            $config['y_axis'] = $min / 2;
            $config['width'] = $data['image_width'];
            $config['height'] = $data['image_width'];
            $t->image_lib->initialize($config);
            $t->image_lib->crop();
        } else {
            $config['width'] = $data['image_width'] - 1;
            $config['height'] = $data['image_height'] - 1;
            $t->image_lib->initialize($config);
            $t->image_lib->resize();
        }

        $config['quality'] = '100%';//возвращаем качество миниатюрам
        $config['create_thumb'] = FALSE;
        $config['maintain_ratio'] = FALSE;
        foreach ($tmbSizes as $item) {
            if ($crop && $crop_size == $item) {
                $config['maintain_ratio'] = FALSE;
                $config['source_image'] = $crop_file;
                $config['new_image'] = $path . $item . DIRECTORY_SEPARATOR . $newFile . $data['file_ext'];
                $parts = preg_split("/[_]+/", $item);
                $config['height'] = $parts[0];
                $config['width'] = $parts[1];
                $t->image_lib->initialize($config);
            } else {
                $config['source_image'] = $source_file;
                $config['maintain_ratio'] = TRUE;
                $config['new_image'] = $path . $item . DIRECTORY_SEPARATOR . $newFile . $data['file_ext'];
                $parts = preg_split("/[_]+/", $item);
                $config['height'] = $parts[0];
                $config['width'] = $parts[1];
                $t->image_lib->initialize($config);
            }

            if (!$t->image_lib->resize()) {
                return $t->image_lib->display_errors();
            }
        }

        return array('result' => TRUE, 'file' => $newFile . $data['file_ext']);
    }

    /**
     * Копирование изображений
     * копирует файлы из одной директории в другую, использует массив размеров миниатюр
     * @param array $files - массив имен файлов для копирвоания
     * @param string $sDir - исходная дириктория
     * @param string $dDir - директория назначения
     * @return boolean - TRUE - все успешно
     */
    public static function batch_copy($files, array $sizes, $sDir, $dDir)
    {
        if (!empty($files)) {
            foreach ($files as $item) {
                @copy($sDir . $item, $dDir . $item);

                foreach ($sizes as $size) {
                    @copy($sDir . $size . DIRECTORY_SEPARATOR . $item, $dDir . $size . DIRECTORY_SEPARATOR . $item);
                }
            }
        }
        return TRUE;
    }

    /**
     * Удаление изображений
     * удаляет файлы, использует массив размеров миниатюр для обхода директорий в которых хранятся изображения
     * @param array $files - массив имен файлов для копирвоания
     * @param string $sDir - исходная дириктория
     * @param array $sizes - размеры
     * @return boolean - TRUE - все успешно
     */
    public static function batch_delete(array $files, array $sizes, $sDir)
    {
        if (!empty($files)) {
            foreach ($files as $item) {
                foreach ($sizes as $s) {
                    if (!@unlink($sDir . $s . DIRECTORY_SEPARATOR . $item)) {
                        return FALSE;
                    }
                }
                if (!@unlink($sDir . $item)) {
                    return FALSE;
                }
            }
        }
        return TRUE;
    }

}