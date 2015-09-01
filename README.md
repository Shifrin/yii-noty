# yii-noty
Yii widget for Noty jQuery notification plugin, [Visit Noty](http://ned.im/noty/#/about)

## Installation:
Download the zip file and unzip it in your project extension folder `webroot/protected/extensions/` and rename `yii-noty-master` to `yii-noty`

## Usage Details:
Add the widget in your main layout file like below,
```
$this->widget('ext.yii-noty.ENotificationWidget', array(
    'options' => array( // you can add js options here, see noty plugin page for available options
      'dismissQueue' => true,
      'layout' => 'topCenter',
      'theme' => 'relax',
      'animation' => array(
      'open' => 'animated flipInX',
      'close' => 'animated flipOutX',
      'easing' => 'swing',
      'speed ' => 500,
    ),
    'timeout' => 6000,
  ),
  'enableIcon' => true,
  'enableFontAwesomeCss' => true,
));
```

This widget will create a JS function `generateAlert()`, so it will be available globally and you can call this function in your custom JS codes. See the following example, after you delete a record from your grid show deleted successful message like below,
```
'afterDelete' => 'function(link,success,data) {
  if (success) {
    var flash = generateAlert();
    
    $.noty.setText(flash.options.id, "<i class=\"fa fa-check-circle\"></i> <strong>Success!</strong> Deleted successfully.");
    $.noty.setType(flash.options.id, "success");
  }
}',
```

You can set the flash messages like this in your controller or anywhere you need,
```
Yii::app()->user->setFlash('type', 'Message here');
```
Replace `type` with available types
  
### Available Types:
  * success
  * error
  * warning
  * information
  * alert

