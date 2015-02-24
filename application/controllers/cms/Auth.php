<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'application/utils/base_controller.php';


class Auth extends BaseController
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->library('form_validation');
        parent::connect_db();
        $this->lang->load('auth');
        parent::set_lang(NULL, ['main', 'ion_auth', 'auth', 'form_validation_lang']);
    }

    /**
     * Отправка письма (восстановление пароля)
     * @param string $email
     * @param $lang_code
     */
    static function post_forgotten_password_successful($email, $lang_code)
    {
        $_this = parent::get_instance();
        $_this->lang->load('ion_auth_lang', $_this->lang_name_);
        $_this->load->library('email');
        $_this->config->load('email');

        $_this->email->from($_this->config->item('email_from'));
        $_this->email->to($_this->input->post('email', TRUE));
        $_this->email->subject(lang('reset_pwd_heading'));

        $identity = $_this->ion_auth->where('email', strtolower($email))->users()->row();

        $_this->data = [
            'forgotten_password_code' => $identity->forgotten_password_code,
            'identity' => $email
        ];

        $_this->email->message($_this->load->view('auth/email/forgot_password.tpl.php', $_this->data, TRUE));

        if ($_this->email->send()) {
            parent::partialViewResult('l_master', 'auth/forgot_password_send_email');
        } else {
            parent::error();
        }
    }

    /**
     * Отправка письма (регистрация)
     * @param string $email
     */
    static function post_account_creation_successful($email, $lang_code, $reactivate = FALSE)
    {
        $_this = parent::get_instance();
        $_this->lang->load('ion_auth_lang', $_this->lang_name_);

        $_this->load->library('email');
        $_this->config->load('email');

        $_this->email->from($_this->config->item('email_from'));
        $_this->email->to($email);
        $_this->email->subject(lang('register_activate_head'));

        $identity = $_this->ion_auth->where('email', strtolower($email))->users()->row();

        $_this->data = [
            'identity' => $identity->email,
            'activation' => $identity->activation_code,
            'id' => $identity->id
        ];

        $_this->email->message($_this->load->view('auth/email/activate.tpl.php', $_this->data, TRUE));

        if ($_this->email->send()) {
            !$reactivate ?
                parent::partialViewResult('l_master', 'auth/reg_activate', $_this->data) :
                parent::partialViewResult('l_master', 'auth/reg_reactivate', $_this->data);
        } else {
            parent::error();
        }
    }

    function index()
    {
        redirect('auth/login');
    }

    function lang($lang_code, $uri = NULL)
    {
        empty($lang_code) ? $this->ion_auth->set_lang('ru') : $this->ion_auth->set_lang($lang_code);
        $this->load->helper('security');
        empty($uri) ? redirect('auth/login') : redirect(xss_clean(base64_decode($uri)));
    }

    /**
     * Логин
     */
    function login()
    {
        if ($this->input->is_get() && $this->ion_auth->logged_in()) {
            $this->ion_auth->redirect_to_default_route();
        } else {
            //validate form input
            $this->form_validation->set_rules('identity', lang('login_email'), 'trim|required|max_length[50]');
            $this->form_validation->set_rules('password', lang('login_password'), 'trim|required|max_length[20]');

            $this->data['identity'] = array('name' => 'identity',
                'id' => 'identity',
                'type' => 'text'
            );

            $this->data['password'] = array('name' => 'password',
                'id' => 'password',
                'type' => 'password',
            );

            if ($this->form_validation->run() == true) {

                $remember = (bool)$this->input->post('remember');

                if ($this->ion_auth->identity_check($this->input->post('identity')) && !$this->ion_auth->is_activate($this->input->post('identity'))) {
                    $identity = trim(base64_encode($this->input->post('identity')), '=');
                    $this->form_validation->set_custom_field_error('user_not_activate',
                        $this->lang->line('login_not_activate') . anchor(base_url("cms/auth/reactivate/$identity"), $this->lang->line('login_not_reactivate'), 'class=reactivate'));
                    parent::partialViewResult('cms/cms_master', "cms/auth/login", $this->data);
                    return;
                }

                if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) {
                    redirect("cms/auth/login");
                } else {
                    $this->form_validation->set_custom_error($this->ion_auth->errors());
                    parent::partialViewResult('cms/cms_master', "cms/auth/login", $this->data);
                }
            } else {
                parent::partialViewResult('cms/cms_master', "cms/auth/login", $this->data);
            }
        }
    }

    /**
     * Выход
     */
    function logout()
    {
        $this->ion_auth->logout();
        redirect('/cms');
    }

    /**
     * Изменение пароля
     */
    function change_password()
    {
        parent::is_logged_in(['admin', 'members']);

        $this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
        $this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
        $this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

        $user = $this->ion_auth->user()->row();


        $this->data['old_password'] = array(
            'name' => 'old',
            'id' => 'old',
            'type' => 'password',
        );
        $this->data['new_password'] = array(
            'name' => 'new',
            'id' => 'new',
            'type' => 'password'
        );
        $this->data['new_password_confirm'] = array(
            'name' => 'new_confirm',
            'id' => 'new_confirm',
            'type' => 'password'
        );
        $this->data['user_id'] = array(
            'name' => 'user_id',
            'id' => 'user_id',
            'type' => 'hidden',
            'value' => $user->id,
        );

        if ($this->form_validation->run() == FALSE) {
            $this->form_validation->set_custom_error($this->ion_auth->errors());
            parent::partialViewResult('cms/cms_master', 'cms/auth/change_password', $this->data);
        } else {
            $identity = $this->session->userdata($this->config->item('identity', 'ion_auth'));

            $change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

            if ($change) {
                $this->ion_auth->logout();
                $this->ion_auth->login($identity, $this->input->post('new'), TRUE);
                $this->ion_auth->redirect_to_default_route();
            } else {
                $this->form_validation->set_custom_error($this->ion_auth->errors());
                parent::partialViewResult('cms/cms_master', 'cms/auth/change_password', $this->data);
            }
        }
    }

    /**
     * Востановление пароля
     */
    function forgot_password()
    {
        $this->form_validation->set_rules('email', $this->lang->line('login_email'), 'required');
        if ($this->form_validation->run() == false) {
            //setup the input
            $this->data['email'] = array('name' => 'email',
                'id' => 'email',
            );

            if ($this->config->item('identity', 'ion_auth') == 'username') {
                $this->data['identity_label'] = $this->lang->line('register_name');
            } else {
                $this->data['identity_label'] = $this->lang->line('login_email');
            }

            //set any errors and display the form
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            parent::partialViewResult('l_master', 'auth/forgot_password', $this->data);
        } else {
            // get identity for that email
            $identity = $this->ion_auth->where('email', strtolower($this->input->post('email')))->users()->row();

            if (empty($identity)) {
                parent::partialViewResult('l_master', 'auth/forgot_password_send_email');
                return;
            }

            $this->ion_auth->set_hook('post_forgotten_password_successful', 'forgot_pass', 'Auth', 'post_forgotten_password_successful', [$this->input->post('email'), $this->lang_code]);

            $forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

            if (!$forgotten) {
                parent::error();
            }
        }
    }


    /**
     * Сброс пароля
     * @param null $code
     */
    public function reset_password($code = NULL)
    {
        if (!$code) {
            parent::_404();
        }

        $user = $this->ion_auth->forgotten_password_check($code);

        if ($user) {
            //if the code is valid then display the password reset form

            $this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
            $this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

            if ($this->form_validation->run() == false) {
                $this->data['user_id'] = array(
                    'name' => 'user_id',
                    'id' => 'user_id',
                    'type' => 'hidden',
                    'value' => $user->id,
                );

                $this->data['csrf'] = $this->_get_csrf_nonce();
                $this->data['code'] = $code;

                parent::partialViewResult('l_master', 'auth/reset_password', $this->data);
            } else {
                // do we have a valid request?
                if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id')) {

                    $this->ion_auth->clear_forgotten_password_code($code);
                    parent::error();
                } else {
                    // finally change the password
                    $identity = $user->{$this->config->item('identity', 'ion_auth')};

                    $change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

                    if ($change) {
                        //if the password was successfully changed
                        $this->session->set_flashdata('message', $this->ion_auth->messages());
                        $this->logout();
                    } else {
                        $this->session->set_flashdata('message', $this->ion_auth->errors());
                        redirect('auth/reset_password/' . $code);
                    }
                }
            }
        } else {
            redirect("auth/forgot_password");
        }
    }

    /**
     * Активация учетной записи
     * @param number $id - идентификатор учетной записи
     * @param bool|string $code - активационый код
     */
    function activate($id, $code = false)
    {
        if ($code !== false) {
            $activation = $this->ion_auth->activate($id, $code);
        } else {
            parent::_404();
        }

        if ($activation) {
            parent::partialViewResult('l_master', 'auth/reg_complete');
        } else {
            parent::partialViewResult('l_master', 'auth/reg_activate_has_complete');
        }
    }

    /**
     * Реактивация учетной записи
     * @param $identity
     */
    function reactivate($identity)
    {
        $identity = base64_decode($identity);
        self::post_account_creation_successful($identity, $this->lang_code, FALSE);
    }

    function _get_csrf_nonce()
    {
        $this->load->helper('string');
        $key = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);
    }

    function _valid_csrf_nonce()
    {
        if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
            $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue')
        ) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
