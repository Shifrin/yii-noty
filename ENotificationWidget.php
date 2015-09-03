<?php

/**
 * ENotificationWidget Class File
 * 
 * It's a widget for messages that can be shown to the end user
 * This widget build with noty jQuery plugin v2.3.5
 * @link http://ned.im/noty/
 *
 * @author Mohammad Shifreen
 * @link http://www.yiiframework.com/extension/yii-noty/
 * @copyright 2015 Mohammed Shifreen
 * @license https://github.com/Shifrin/yii-noty/blob/master/LICENSE.md
 */
class ENotificationWidget extends CWidget {
    
    public $id = 'notification-widget';
    public $options = array();
    public $enableUserFlash = true;
    public $userComponentId = 'user';
    public $enableIcon = false;
    public $enableFontAwesomeCss = false;
    public $enablebuttonCss = false;
    protected $types = array(
        'error' => 'error',
        'success' => 'success',
        'info' => 'information',
        'warning' => 'warning',
        'alert' => 'alert'
    );
    protected $icons = array(
        'error' => 'fa fa-times-circle',
        'success' => 'fa fa-check-circle',
        'information' => 'fa fa-info-circle',
        'warning' => 'fa fa-exclamation-circle',
        'alert' => 'fa fa-bell-o',
        'notification' => 'fa fa-bell-o',
    );

    public function init() {
        $this->registerAssets();
        
        if (isset($this->options['test'])) {
            unset($this->options['text']);
        }
        
        if (isset($this->options['type'])) {
            unset($this->options['type']);
        }
    }

    public function run() {
        $id = $this->id;
        $options = CJavaScript::encode($this->options);
        $cs = Yii::app()->clientScript;
        $js = '';
        $messages = '';
        
        if ($this->enableUserFlash) {
            $user = Yii::app()->getComponent($this->userComponentId);
            $messages = $user->getFlashes();
        }
        
        foreach ($messages as $key => $value) {
            if (empty($value)) {
                continue;
            }
            
            $type = $this->verifyType($key);
            $icon = $this->getIcon($type);
            $text = is_array($value) ? implode(' ', $value) : $value;
            $message = $icon . $text;
            $js .= "var $type = generateAlert('$id');\n";
            $js .= '$.noty.setText(' . $type . '.options.id, \'' . $message . '\');' . "\n";
            $js .= '$.noty.setType(' . $type . '.options.id, \'' . $type . '\');' . "\n";
        }
        
        // Registering the script here, it will be available for globaly
        $cs->registerScript("noty#{$id}", "
            function generateAlert(widgetId, options) {
                var finalOptions = $.extend({}, $options, options);
                var n = noty(finalOptions);
                
                return n;
            }
            
            $js
            "
        , CClientScript::POS_READY);
    }

    /**
     * Register necessary noty scripts and styles
     */
    protected function registerAssets() {
        $dir = dirname(__FILE__) . '/assets';
        $assetsDir = Yii::app()->assetManager->publish($dir);
        $cs = Yii::app()->clientScript;

        // Required CSS for noty
        $cs->registerCssFile($assetsDir . '/css/animate.css');
        // Register only if it is required
        if ($this->enablebuttonCss) {
            $cs->registerCssFile($assetsDir . '/css/buttons.css');
        }
        // Register only if it is required
        if ($this->enableFontAwesomeCss) {
            $cs->registerCssFile($assetsDir . '/css/font-awesome.min.css');
        }
        
        // jQuery
        $cs->registerCoreScript('jquery');
        // noty JS
        $cs->registerScriptFile($assetsDir . '/js/noty/packaged/jquery.noty.packaged.min.js', CClientScript::POS_END);
        
        // noty theme JS
        if ($this->options['theme'] == 'bootstrapTheme') {
            $cs->registerScriptFile($assetsDir . '/js/noty/themes/bootstrap.js', CClientScript::POS_END);
        } elseif ($this->options['theme'] == 'relax') {
            $cs->registerScriptFile($assetsDir . '/js/noty/themes/relax.js', CClientScript::POS_END);
        } else {
            $cs->registerScriptFile($assetsDir . '/js/noty/themes/default.js', CClientScript::POS_END);
        }
    }
    
    /**
     * Verify type, if not return defalut type
     * @param string $type Type to verify
     * @return string
     */
    protected function verifyType($type) {
        if (array_key_exists($type, $this->types)) {
            return $this->types[$type];
        }
        
        // Return default
        return 'notification';
    }
    
    /**
     * Get icon according to the type
     * @param string $type
     */
    protected function getIcon($type) {
        if (!$this->enableIcon) {
            return '';
        }
        
        $class = $this->icons[$type];
        
        return CHtml::tag('i', array('class' => $class), '') . ' ';
    }

}
