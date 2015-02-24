<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Name:  Ion Auth Lang - Russian (UTF-8)
 *
 * Author: Ben Edmunds
 * 		  ben.edmunds@gmail.com
 *         @benedmunds
 * Translation:  Petrosyan R.
 *             for@petrosyan.rv.ua
 *
 * Location: http://github.com/benedmunds/ion_auth/
 *
 * Created:  03.26.2010
 *
 * Description:  Russian language file for Ion Auth messages and errors
 *
*/

// Account Creation
$lang['account_creation_successful'] 	  	 = 'Учетная запись успешно создана';
$lang['account_creation_unsuccessful'] 	 	 = 'Невозможно создать учетную запись';
$lang['account_creation_duplicate_email'] 	 = 'Электронная почта уже существует';
$lang['account_creation_duplicate_username'] = 'Имя пользователя существует или некорректно';

// Password
$lang['password_change_successful'] 	 	 = 'Пароль успешно изменен';
$lang['password_change_unsuccessful'] 	  	 = 'Пароль невозможно изменить';
$lang['forgot_password_successful'] 	 	 = 'Пароль сброшен. На электронную почту отправлено сообщение';
$lang['forgot_password_unsuccessful'] 	 	 = 'Невозможен сброс пароля';

// Activation
$lang['activate_successful'] 		  	 = 'Учетная запись активирована';
$lang['activate_unsuccessful'] 		 	 = 'Не удалось активировать учетную запись';
$lang['deactivate_successful'] 		  	 = 'Учетная запись деактивирована';
$lang['deactivate_unsuccessful'] 	  	 = 'Невозможно деактивировать учетную запись';
$lang['activation_email_successful'] 	 = 'Сообщение об активации отправлено';
$lang['activation_email_unsuccessful']   = 'Сообщение об активации невозможно отправить';

// Login / Logout
$lang['login_successful'] 		  	 = 'Авторизация прошла успешно';
$lang['login_unsuccessful'] 		  	 = 'Логин/пароль не верен';
//$lang['logout_successful'] 		 	 = 'Выход успешный';
//$lang['login_unsuccessful_not_active'] 		 = 'Аккаунт активирован';
$lang['login_timeout']                       = 'Превышено число попыток входа.  Попробуйте позднее.';
$lang['login_head']  = 'Личный кабинет';
$lang['login_login']  = 'Вход';
$lang['login_email'] = 'Email';
$lang['login_password'] = 'Пароль';
//$lang['login_confirm_password'] = 'Подтверждение пароля';
$lang['login_remember'] = 'Запомнить меня';
//$lang['login_forgot_pwd'] = 'Я забыл пароль';
//$lang['login_btn'] = 'Войти';
//$lang['login_not_activate'] = 'Учетная запись не активирована. ';
//$lang['login_not_reactivate'] = 'Выслать письмо повторно';
//$lang['login_cprt'] = '2014 Rubetek, Inc Все права защищены';


// Account Changes
$lang['delete_successful'] 		 	 = 'Учетная запись удалена';
$lang['delete_unsuccessful'] 		 = 'Невозможно удалить учетную запись';

// Email Subjects
//$lang['email_forgotten_password_subject']    = 'Проверка забытого пароля';
//$lang['email_new_password_subject']          = 'Новый пароль';
//$lang['email_activation_subject']            = 'Активация учетной записи';

// Forgot Password Email
$lang['email_forgotten_password_subject']    = 'Forgotten Password Verification';
$lang['email_forgot_password_heading']    = 'Сброс пароля для: %s';
$lang['email_forgot_password_subheading'] = 'Перейдите по ссылке для сброса пароля %s.';
$lang['email_forgot_password_link']       = 'Reset Your Password';