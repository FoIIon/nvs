<?php

abstract class Model{

    public function toString() :self{
        $class_vars = get_class_vars(get_class($this));
		if(count($class_vars) == 0){
			echo 'No properties ' . '<br/>';
		}
        foreach ($class_vars as $name => $value) {
            echo get_class($this).' : '. $name .' : ' . $value .' <br/>';
        }
    }

	function console_log($with_script_tags = true) {
		$js_code = 'console.log(' . json_encode($this) . 
	');';
		if ($with_script_tags) {
			$js_code = '<script>' . $js_code . '</script>';
		}
		echo $js_code;
	}
}