<?php
/**
 * Forms.
 *
 * @package MASElementor\Modules\Forms
 */

namespace MASElementor\Modules\Forms;

use MASElementor\Base\Module_Base;
use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Form module class
 */
class Module extends Module_Base {
	/**
	 * Get name.
	 */
	public function get_name() {
		return 'mas-forms';
	}

	/**
	 * Get widgets.
	 */
	public function get_widgets() {
		return array(
			// 'Login',
			'Signin',
		);
	}

	/**
	 * Instantiate the class.
	 */
	public function __construct() {
		parent::__construct();
		add_action( 'elementor/frontend/before_register_scripts', array( $this, 'register_frontend_scripts' ) );
		add_action( 'wp_loaded', array( $this, 'mas_add_new_member' ) );
		add_action( 'wp_loaded', array( $this, 'mas_login_member' ) );
		add_action( 'wp_loaded', array( $this, 'mas_lost_password' ) );
	}

	/**
	 * Get data if set, otherwise return a default value or null. Prevents notices when data is not set.
	 *
	 * @param  mixed  $var     Variable.
	 * @param  string $default Default value.
	 * @return mixed
	 */
	public function mas_get_var( &$var, $default = null ) {
		return isset( $var ) ? $var : $default;
	}

	/**
	 * Landkit Form errors.
	 *
	 * @return WP_Error
	 */
	public function mas_form_errors() {
		static $wp_error; // Will hold global variable safely.
		if ( ! isset( $wp_error ) ) {
			$wp_error = new WP_Error( null, null, null );
		}
		return $wp_error;

	}

	/**
	 * Landkit Form Success.
	 *
	 * @return WP_Error
	 */
	public function mas_form_success() {
		static $wp_error; // Will hold global variable safely.
		if ( ! isset( $wp_error ) ) {
			$wp_error = new WP_Error( null, null, null );
		}
		return $wp_error;
	}

	/**
	 * Add new user.
	 */
	public function mas_add_new_member() {
		$nonce_value = $this->mas_get_var( $_REQUEST['mas_register_nonce'], $this->mas_get_var( $_REQUEST['_wpnonce'], '' ) ); //phpcs:ignore
		if ( isset( $_POST['mas_register_check'], $_POST['email'] ) && wp_verify_nonce( $nonce_value, 'mas-register-nonce' ) ) {
			$register_user_name_enabled = apply_filters( 'mas_register_user_name_enabled', true );
			$default_role               = 'subscriber';
			$available_roles            = array( 'subscriber' );

			if ( function_exists( 'mas_is_wp_job_manager_activated' ) && mas_is_wp_job_manager_activated() ) {
				$available_roles[] = 'employer';
			}

			$user_email = wp_unslash( $_POST['email'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$user_role  = ! empty( $_POST['mas_user_role'] ) && in_array( $_POST['mas_user_role'], $available_roles, true ) ? sanitize_text_field( wp_unslash( $_POST['mas_user_role'] ) ) : $default_role;

			if ( ! empty( $_POST['username'] ) ) {
				$user_login = wp_unslash( $_POST['username'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			} else {
				$user_login = sanitize_user( current( explode( '@', $user_email ) ), true );

				// Ensure username is unique.
				$append       = 1;
				$o_user_login = $user_login;

				while ( username_exists( $user_login ) ) {
					$user_login = $o_user_login . $append;
					$append++;
				}
			}

			if ( username_exists( $user_login ) && $register_user_name_enabled ) {
				// Username already registered.
				$this->mas_form_errors()->add( 'username_unavailable', esc_html__( 'Username already taken', 'mas-addons-for-elementor' ) );
			}
			if ( ! validate_username( $user_login ) && $register_user_name_enabled ) {
				// invalid username.
				$this->mas_form_errors()->add( 'username_invalid', esc_html__( 'Invalid username', 'mas-addons-for-elementor' ) );
			}
			if ( '' === $user_login && $register_user_name_enabled ) {
				// empty username.
				$this->mas_form_errors()->add( 'username_empty', esc_html__( 'Please enter a username', 'mas-addons-for-elementor' ) );
			}
			if ( ! is_email( $user_email ) ) {
				// invalid email.
				$this->mas_form_errors()->add( 'email_invalid', esc_html__( 'Invalid email', 'mas-addons-for-elementor' ) );
			}
			if ( email_exists( $user_email ) ) {
				// Email address already registered.
				$this->mas_form_errors()->add( 'email_used', esc_html__( 'Email already registered', 'mas-addons-for-elementor' ) );
			}

			$password           = wp_generate_password();
			$password_generated = true;

			if ( apply_filters( 'mas_register_password_enabled', true ) && ! empty( $_POST['password'] ) && ! empty( $_POST['confirmPassword'] ) ) {
				$password           = $_POST['password']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
				$password_generated = false;
			}

			if ( $_POST['password'] !== $_POST['confirmPassword'] ) {
				// Mismatched Password.
				$this->mas_form_errors()->add( 'wrong_password', esc_html__( 'Password you entered is mismatched', 'mas-addons-for-elementor' ) );
			}

			do_action( 'mas_wp_register_form_custom_field_validation' );

			$errors = $this->mas_form_errors()->get_error_messages();

			// only create the user in if there are no errors.
			if ( empty( $errors ) ) {

				$new_user_data = array(
					'user_login' => $user_login,
					'user_pass'  => $password,
					'user_email' => $user_email,
					'role'       => $user_role,
				);

				$new_user_id = wp_insert_user( $new_user_data );

				if ( $new_user_id ) {
					// send an email to the admin alerting them of the registration.
					if ( apply_filters( 'mas_new_user_notification', false ) ) {
						wc()->mailer()->customer_new_account( $new_user_id, $new_user_data, $password_generated );
					} else {
						wp_new_user_notification( $new_user_id, null, 'both' );
					}

					// log the new user in.
					$creds                  = array();
					$creds['user_login']    = $user_login;
					$creds['user_password'] = $password;
					$creds['remember']      = true;
					if ( $password_generated ) {
						$this->mas_form_success()->add( 'verify_user', esc_html__( 'Account created successfully. Please check your email to create your account password', 'mas-addons-for-elementor' ) );
					} else {
						$user = wp_signon( $creds, false );
						// send the newly created user to the home page after logging them in.
						if ( is_wp_error( $user ) ) {
							echo wp_kses_post( $user->get_error_message() );
						} else {
							$o_user = get_user_by( 'login', $creds['user_login'] );
							$a_user = get_object_vars( $o_user );
							$s_role = $a_user['roles'][0];

							if ( get_option( 'woocommerce_myaccount_page_id' ) ) {
								$account_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
							} else {
								$account_url = home_url( '/' );
							}

							if ( get_option( 'job_manager_job_dashboard_page_id' ) ) {
								$job_url = get_permalink( get_option( 'job_manager_job_dashboard_page_id' ) );
							} else {
								$job_url = home_url( '/' );
							}

							switch ( $s_role ) {
								case 'subscriber':
									$redirect_url = $account_url;
									break;
								case 'employer':
									$redirect_url = $job_url;
									break;

								default:
									$redirect_url = home_url( '/' );
									break;
							}

							wp_safe_redirect( apply_filters( 'mas_redirect_register_url', $redirect_url, $user ) );
						}
						exit;
					}
				}
			}
		}
	}

	/**
	 * Logs a member in after submitting a form.
	 */
	public function mas_login_member() {
		$nonce_value = $this->mas_get_var( $_REQUEST['mas_login_nonce'], $this->mas_get_var( $_REQUEST['_wpnonce'], '' ) ); //phpcs:ignore 
		if ( isset( $_POST['mas_login_check'], $_POST['username'], $_POST['password'] ) && wp_verify_nonce( $nonce_value, 'mas-login-nonce' ) ) {
			$username = trim( wp_unslash( $_POST['username'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

			// this returns the user ID and other info from the user name.
			if ( is_email( $username ) ) {
				$user = get_user_by( 'email', $username );
			} else {
				$user = get_user_by( 'login', $username );
			}

			if ( ! $user ) {
				// if the user name doesn't exist.
				$this->mas_form_errors()->add( 'empty_username', esc_html__( 'Invalid username or email address', 'mas-addons-for-elementor' ) );
			}

			do_action( 'mas_wp_login_form_custom_field_validation' );

			if ( ! empty( $user ) ) {
				$password = $_POST['password']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash

				if ( ! isset( $password ) || '' === $password ) {
					// if no password was entered.
					$this->mas_form_errors()->add( 'empty_password', esc_html__( 'Please enter a password', 'mas-addons-for-elementor' ) );
				}

				if ( isset( $password ) && ! empty( $password ) ) {
					// check the user's login with their password.
					if ( ! wp_check_password( $password, $user->user_pass, $user->ID ) ) {
						// if the password is incorrect for the specified user.
						$this->mas_form_errors()->add( 'empty_password', esc_html__( 'Incorrect password', 'mas-addons-for-elementor' ) );
					}
				}

				// retrieve all error messages.
				$errors = $this->mas_form_errors()->get_error_messages();

				// only log the user in if there are no errors.
				if ( empty( $errors ) ) {

					$creds                  = array();
					$creds['user_login']    = $user->user_login;
					$creds['user_password'] = $password;
					$creds['remember']      = true;

					$user = wp_signon( $creds, false );
					// send the newly created user to the home page after logging them in.
					if ( is_wp_error( $user ) ) {
						echo wp_kses_post( $user->get_error_message() );
					} else {
						$o_user = get_user_by( 'login', $creds['user_login'] );
						$a_user = get_object_vars( $o_user );
						$s_role = $a_user['roles'][0];

						if ( isset( $_POST['redirect_to'] ) && ! empty( $_POST['redirect_to'] ) ) {
							$redirect_url = wp_sanitize_redirect( wp_unslash( $_POST['redirect_to'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
						} else {
							$redirect_url = home_url( '/' );
						}

						wp_safe_redirect( wp_validate_redirect( apply_filters( 'mas_redirect_login_url', $redirect_url ) ) );
					}
					exit;
				}
			}
		}
	}

	/**
	 * Landkit Lost Password function.
	 */
	public function mas_lost_password() {
		$nonce_value = $this->mas_get_var( $_REQUEST['mas_lost_password_nonce'], $this->mas_get_var( $_REQUEST['_wpnonce'], '' ) ); //phpcs:ignore
		if ( isset( $_POST['mas_lost_password_check'] ) && wp_verify_nonce( $nonce_value, 'mas-lost-password-nonce' ) ) {
			$login     = isset( $_POST['user_login'] ) ? sanitize_user( wp_unslash( $_POST['user_login'] ) ) : '';
			$user_data = get_user_by( 'login', $login );

			if ( empty( $login ) ) {
				$this->mas_form_errors()->add( 'empty_user_login', esc_html__( 'Enter a username or email address', 'mas-addons-for-elementor' ) );

			} else {
				// Check on username first, as customers can use emails as usernames.
				$user_data = get_user_by( 'login', $login );
			}
			// If no user found, check if it login is email and lookup user based on email.
			if ( ! $user_data && is_email( $login ) ) {
				$user_data = get_user_by( 'email', $login );
			}

			do_action( 'lostpassword_post' );

			if ( ! $user_data ) {
				// if the user name doesn't exist.
				$this->mas_form_errors()->add( 'empty_user_login', esc_html__( 'There is no account with that username or email address.', 'mas-addons-for-elementor' ) );
			}

			if ( is_multisite() && ! is_user_member_of_blog( $user_data->ID, get_current_blog_id() ) ) {
				$this->mas_form_errors()->add( 'empty_user_login', esc_html__( 'Invalid username or email address.', 'mas-addons-for-elementor' ) );

				return false;
			}

			$errors = $this->mas_form_errors()->get_error_messages();

			// only create the user in if there are no errors.
			if ( empty( $errors ) ) {
				$this->mas_form_success()->add( 'verify_user', esc_html__( 'Passord has been sent to your email', 'mas-addons-for-elementor' ) );

			}
		}

	}

	/**
	 * Register frontend script.
	 */
	public function register_frontend_scripts() {
		wp_register_script(
			'forgot-password',
			MAS_ELEMENTOR_MODULES_URL . 'forms/assets/js/forgot-password.js',
			array( '' ),
			MAS_ELEMENTOR_VERSION,
			true
		);
	}

}
