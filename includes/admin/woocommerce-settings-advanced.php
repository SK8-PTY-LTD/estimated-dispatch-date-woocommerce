<?php
/**
 * WooCommerce General Settings
 *
 * @author      WooThemes
 * @category    Admin
 * @package     WooCommerce/Admin
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WooCommerce_Advanced_Settings' ) ) :

/**
 * WC_Admin_Settings_General
 */
class WooCommerce_Advanced_Settings extends WC_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {

		$this->id    = 'wc_intergation_advanced';
		$this->label = __( 'WC Advanced Intergation', EDDWC_TXT );

		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
        add_filter( 'woocommerce_sections_'.$this->id,      array( $this, 'output_sections' ));
        add_filter( 'woocommerce_settings_'.$this->id,      array( $this, 'output_settings' )); 
		add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );
	}

    /**
     * Get sections
     *
     * @return array
     */
    public function get_sections() {

        $sections = array(
            ''            => __( 'Simple Inputs', EDDWC_TXT ),
            'advanced_input'     => __( 'Advanced Inputs',EDDWC_TXT ),
            'predefined_input' => __('Predefined Inputs',EDDWC_TXT ), 
        );


        return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
    }
    
    
    
    
    
    
    public function output_settings(){
        global $current_section;
        $settings = $this->get_settings( $current_section ); 
        WC_Admin_Settings::output_fields( $settings );
    }    
    
    
	/**
	 * Get settings array
	 *
	 * @return array
	 */
	public function get_settings($section = null) { 
        var_dump($section);
		$settings = array(

			array( 
                'title' => __( 'Simple Inputs', EDDWC_TXT ), 
                'type' => 'title', 
                'desc' => '', 
                'id' => 'wc_advanced_simple_inputs_intergation' 
            ),

			array(
				'title'    => __( 'Text Box', EDDWC_TXT ),
				'desc'     => __( 'This is a simple textbox', EDDWC_TXT ),
				'id'       =>'wc_simple_textbox',
				'css'      => 'min-width:350px;',
				'default'  => 'Simple TextBox',
				'type'     => 'text',
				'desc_tip' =>  true,
			),

            array(
				'title'    => __( 'TextArea', EDDWC_TXT ),
				'id'       => 'wc_simple_textarea',
				'default'  => __( 'Simple TextArea.', EDDWC_TXT ),
				'type'     => 'textarea',
				'css'     => 'width:350px; height: 65px;',
				'autoload' => false
			),
            
			array(
				'title'    => __( 'Radio Buttons', EDDWC_TXT ),
				'desc'     => __( 'A Simple Radio Button', EDDWC_TXT ),
				'id'       => 'wc_simple_radio',
				'default'  => 'all',
				'type'     => 'radio',  
				'desc_tip' =>  true,
				'options'  => array(
					'option1'  => __( 'option 1', EDDWC_TXT ),
					'option2'  => __( 'option 2', EDDWC_TXT )
				)
			),

			  

			array(
				'title'   => __( 'Option 1', EDDWC_TXT ),
				'id'      => 'wc_simple_checkbox_1',
				'default' => 'no',
				'type'    => 'checkbox',
			),
            
            array(
				'title'   => __( 'Option 2', EDDWC_TXT ),
				'id'      => 'wc_simple_checkbox_2',
				'default' => 'no',
				'type'    => 'checkbox',
			), 



			array( 'type' => 'sectionend', 'id' => 'wc_advanced_simple_inputs_intergation'),

			     

		);

		return $settings;
	}
 

	/**
	 * Save settings
	 */
	public function save() {
		$settings = $this->get_settings();

		WC_Admin_Settings::save_fields( $settings );
	}

}

endif;

return new WooCommerce_Advanced_Settings();
