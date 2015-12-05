<?php
class Estimated_Dispatch_Date_For_WooCommerce_Admin_Options {
    private $page_hook = '';
    public $settings;
    private $settings_page;
    private $settings_section;
    private $settings_fields;
    private $create_function;
    private $settings_key;
    private $settings_values;
	
    function __construct($page_hook = '') {
        $this->settings_section = array();
        $this->settings_fields = array();
        $this->create_function = array();
        $this->add_settings_pages();
        $this->get_settings();
        $this->add_settings_section();
        $this->create_callback_function();
		$this->page_hook = $page_hook; 
        if(empty($page_hook)) {
            add_action( 'admin_menu', array( $this, 'admin_menu' ), 99);
        }
        add_action( 'admin_init', array( $this, 'admin_init' ) );
    }
    
    
    function admin_menu() { 
		$this->page_hook = add_submenu_page('woocommerce',
											__('Estimated Dispatch Date',EDDWC_TXT),
											__('Estimated Dispatch Date',EDDWC_TXT),
											'administrator',EDDWC_DB.'settings', array( $this, 'admin_page' ) );
	}
    
    
    private function add_settings_pages(){
        global $pages;
        $pages =  array();
        include(EDDWC_SETTINGS.'pages.php');
        $this->settings_page = $pages;
    }
    
    private function add_settings_section(){
        global $section;
        $section =  array();
        include(EDDWC_SETTINGS.'section.php');
        $this->settings_section = $section;
    }
    
    private function create_callback_function(){
        $sec = $this->settings_section;
        
        foreach($sec as $sk => $s){
            if(is_array($s)){
                $c = count($s);
                $a = 0;
                while($a < $c){
                    if(isset($s[$a]['validate_callback'])){
                        $this->create_function[] =  $s[$a]['id'];
                        $s[$a]['validate_callback'] = '';
                        $file = addslashes(EDDWC_SETTINGS.'validate-'.$s[$a]['id'].'.php');
                        $s[$a]['validate_callback'] = create_function('$fields', 'global $send_fields; $send_fields =  $fields; if(file_exists("'.$file.'")) { require("'.$file.'"); } return $fields;');
                    }
                    $a++;
                }
            }
            
            $this->settings_section[$sk] = $s; 
        }
    } 
    
    
    private function add_settings_fields(){
        global $fields;
        $fields =  array();
        include(EDDWC_SETTINGS.'fields.php');
        $this->settings_field = $fields;
    }

    function admin_init(){ 
		$this->settings = new Estimated_Dispatch_Date_For_WooCommerce_WP_Settings();
        $this->add_settings_fields();
        $this->settings->add_pages($this->settings_page);
        $sections = $this->settings_section;
        
        foreach ($sections as $page_id => $section_value){
            $pages = $this->settings->add_sections($page_id,$section_value);
        }
        
        $fields = $this->settings_field;
        foreach($fields as $page_id => $section_fields){
            foreach($section_fields as $section_id => $sfields){
                if(is_array($sfields)){
                    foreach($sfields as $f){
                        $pages = $this->settings->add_field($page_id,$section_id,$f);
                    }
                
                } else {
                    $pages = $this->settings->add_field($page_id,$section_id,$sfields);
                }
                
            } 
        }
		
		$this->settings->init($pages, EDDWC_DB );
    }
    
    
    public function admin_page(){
		echo '<div class="wrap wc_qd_settings">';
		settings_errors();
		$this->settings->render_header();
		echo $this->settings->debug;
		$this->settings->render_form();
		echo '</div>';
	}
 
    function get_option($id = ''){
        if( ! empty($this->settings_values) &&  ! empty($id)){
            if(isset($this->settings_values[$id])){
                return $this->settings_values[$id];
            }
        }
        return false;
    
    }
    
    function get_settings($key = ''){
        $values = array();
        foreach($this->settings_page as $settings){
            $this->settings_key[] = EDDWC_DB.$settings['slug'];
            $db_val = get_option(EDDWC_DB.$settings['slug']);
            if(is_array($db_val)){
                unset($db_val['section_id']); 
                $values = array_merge($db_val,$values);
            }
        }
        
        $this->settings_values = $values;
        return $values;
    }
}

?>