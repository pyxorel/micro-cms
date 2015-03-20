<?php if (!defined('BASEPATH')) exit('No direct script access allowed.');

class Morph
{

    protected function toUpperCase($string)
    {
        $small = array('а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й',
            'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф',
            'х', 'ч', 'ц', 'ш', 'щ', 'э', 'ю', 'я', 'ы', 'ъ', 'ь',
            'э', 'ю', 'я');
        $large = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й',
            'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф',
            'Х', 'Ч', 'Ц', 'Ш', 'Щ', 'Э', 'Ю', 'Я', 'Ы', 'Ъ', 'Ь',
            'Э', 'Ю', 'Я');
        return str_replace($small, $large, $string);
    }

    function Highlight($whereText, $whatText)
    {

        $highlightWords = $highlightWordsRepl = array();
        $highlightWordsT = $this->Words2AllForms($whatText);

        foreach ($highlightWordsT as $k => $v)
            if (!$v) {
                $highlightWords[] = "#\b($k)\b#isU";
                $highlightWordsRepl[] = '[highlight]\\1[/highlight]';
            } else
                foreach ($v as $v1) {
                    $highlightWords[] = "#\b($v1)\b#isU";
                    $highlightWordsRepl[] = '[highlight]\\1[/highlight]';
                }

        return $message['message_text'] = preg_replace(array_reverse($highlightWords), '[highlight]$1[/highlight]', $whereText);
    }

    function Words2AllForms($text)
    {
        require_once('application/libs/phpmorphy/src/common.php');

        $opts = array(
            'storage' => PHPMORPHY_STORAGE_MEM,
            'with_gramtab' => false,
            'predict_by_suffix' => true,
            'predict_by_db' => true
        );

        $dir = 'application/libs/phpmorphy/dicts';

        $dict_bundle = new phpMorphy_FilesBundle($dir, 'rus');

        $morphy = new phpMorphy($dict_bundle, $opts);

        $words = preg_split('#\s|[,.:;!?"\'()]#', $text, -1, PREG_SPLIT_NO_EMPTY);

        $bulk_words = array();
        foreach ($words as $v)
            if (strlen($v) > 3)
                $bulk_words[] = $this->toUpperCase($v);
        $tmp = $morphy->getAllForms($bulk_words);

        $el = "";
        foreach ($tmp as $k => $a) {
            if (is_array($a)) {
                $el .= join(" ", $a);
            } else {
                $el .= $k;
            }
        }
        return $el;
    }

    function Words2BaseForm($text)
    {
        if ($text === NULL) {
            return NULL;
        }
        require_once('application/libs/phpmorphy/src/common.php');

        $opts = array(
            'storage' => PHPMORPHY_STORAGE_MEM,
            'with_gramtab' => false,
            'predict_by_suffix' => true,
            'predict_by_db' => true
        );

        $dir = 'application/libs/phpmorphy/dicts';

        $dict_bundle = new phpMorphy_FilesBundle($dir, 'rus');

        $morphy = new phpMorphy($dict_bundle, $opts);

        $words = preg_replace('#\[.*\]#isU', '', $text);
        $words = preg_split('#\s|[,.:;!?"\'()]#', $words, -1, PREG_SPLIT_NO_EMPTY);

        $bulk_words = array();
        foreach ($words as $v)
            if (strlen($v) > 3) {
                $bulk_words[] = $this->toUpperCase($v);
            }

        $base_form = $morphy->getBaseForm($bulk_words);

        $fullList = array();
        if (is_array($base_form) && count($base_form))
            foreach ($base_form as $k => $v)
                if (is_array($v))
                    foreach ($v as $v1)
                        if (strlen($v1) > 3)
                            $fullList[$v1] = 1;

        $words = join(' ', array_keys($fullList));
        return $words;
    }
}
