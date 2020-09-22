<?php
//include_once("inc/xlsxwriter.class.php");
require_once ("../app/Mage.php");
//$app = Mage::app('default');

Mage::app('default');
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
Mage::setIsDeveloperMode(true);

//$products = Mage::getModel("catalog/product")->getCollection();

$map = array(
    'anfahrt' => array(
        'description' => 'Anfahrt',
        'db_field' => '',
        'type' => 'html'
    ),
    'customticketdesign' => array(
        'description' => 'Dateiname Ticketdesign',
        'db_field' => '',
        'type' => 'string'
    ),
    'description' => array(
        'description' => 'Beschreibung',
        'db_field' => '',
        'type' => 'html'
    ),
    'entwurfsid' => array(
        'description' => 'EntwurfsID',
        'db_field' => '',
        'type' => 'int'
    ),
    'eventenddatum' => array(
        'description' => 'Event-Enddatum',
        'db_field' => '',
        'type' => 'date'
    ),
    'eventgruppe' => array(
        'description' => 'Event-Gruppe',
        'db_field' => '',
        'type' => 'string'
    ),
    'eventgruppenbezeichnung' => array(
        'description' => 'Event-Gruppenbezeichnung',
        'db_field' => '',
        'type' => 'string'
    ),
    'eventgruppenreihenfolge' => array(
        'description' => 'Event-Gruppenreihenfolge',
        'db_field' => '',
        'type' => 'int'
    ),
    'eventstartdatum' => array(
        'description' => 'Event-Startdatum',
        'db_field' => '',
        'type' => 'date'
    ),
//    'gallery' => array(
//        'description' => 'Image Gallery',
//        'db_field' => '',
//        'type' => 'imagefile'
//    ),
    'globaleeventid' => array(
        'description' => 'Globale Event ID',
        'db_field' => '',
        'type' => 'string'
    ),
    'globaleveranstalterid' => array(
        'description' => 'Veranstalter ID',
        'db_field' => '',
        'type' => 'string'
    ),
    'image' => array(
        'description' => 'Base Image',
        'db_field' => '',
        'type' => 'imagefile'
    ),
    'mdvberechtigung' => array(
        'description' => 'MDV-Fahrtberechtigung',
        'db_field' => '',
        'type' => 'int'
    ),
    'name' => array(
        'description' => 'Name',
        'db_field' => '',
        'type' => 'string'
    ),
    'originalinstanzid' => array(
        'description' => 'Orginal Instanz ID',
        'db_field' => '',
        'type' => 'string'
    ),
    'price' => array(
        'description' => 'Price',
        'db_field' => '',
        'type' => 'decimal'
    ),
    'price_view' => array(
        'description' => 'Price View',
        'db_field' => '',
        'type' => 'string'
    ),
    'short_description' => array(
        'description' => 'Kurzbeschreibung',
        'db_field' => '',
        'type' => 'string'
    ),
    'sku' => array(
        'description' => 'SKU',
        'db_field' => '',
        'type' => 'string'
    ),
    'small_image' => array(
        'description' => 'Small Image',
        'db_field' => '',
        'type' => 'imagefile'
    ),
    'special_from_date' => array(
        'description' => 'Special Price From Date',
        'db_field' => '',
        'type' => 'date'
    ),
    'special_price' => array(
        'description' => 'Special Price',
        'db_field' => '',
        'type' => 'decimal'
    ),
    'special_to_date' => array(
        'description' => 'Special Price To Date',
        'db_field' => '',
        'type' => 'date'
    ),
    'status' => array(
        'description' => 'Status',
        'db_field' => '',
        'type' => 'int'
    ),
    'tax_class_id' => array(
        'description' => 'Tax Class',
        'db_field' => '',
        'type' => 'int',
        'value_explanation' => array(
            0 => 'keine',
            1 => 'Vollbesteuerte Artikel',
            2 => 'Ermäßigtbesteuerte Artikel',
            3 => 'Vollbesteuerter Versand',
            4 => 'Ermäßigtbesteuerter Versand'
        )
    ),
    'thumbnail' => array(
        'description' => 'Thumbnail',
        'db_field' => '',
        'type' => 'imagefile'
    ),
    'ticketinformationen' => array(
        'description' => 'Ticketinformationen',
        'db_field' => '',
        'type' => 'html'
    ),
    'tiventsproduktstatus' => array(
        'description' => 'tivents Produkt Status',
        'db_field' => '',
        'type' => 'int',
        'value_explanation' => array(
            100 => 'im Entwurf',
            200 => 'wird von tivents geprüft',
            201 => 'vom Veranstalter zurückgezogen',
            202 => 'wurde von tivents abgelehnt',
            300 => 'wird vom Veranstalter nachbearbeitet',
            301 => 'wird von tivents nachbearbeitet',
            400 => 'im Verkauf',
            500 => 'Verkauf beendet',
            501 => 'vom Veranstalter aus dem Verkauf genommen',
            502 => 'Veranstaltung ist ausverkauft',
            503 => 'Veranstaltung wurde vom Veranstalter abgesagt',
            600 => 'beendet und abgerechnet'
        )
    ),
    'url_key' => array(
        'description' => 'URL Key',
        'db_field' => '',
        'type' => 'string'
    ),
    'ursprungslager' => array(
        'description' => 'Ursprungsanzahl',
        'db_field' => '',
        'type' => 'int'
    ),
    'veranstaltungsort' => array(
        'description' => 'Veranstaltungsort',
        'db_field' => '',
        'type' => 'string'
    ),
    'visibility' => array(
        'description' => 'Visibility',
        'db_field' => '',
        'type' => 'int',
        'value_explanation' => array(
            1 => 'Einzeln nicht sichtbar',
            2 => 'Katalog',
            3 => 'Suche',
            4 => 'Katalog, Suche'
        )
    )
);

$collection = Mage::getModel('catalog/product')
    ->getCollection()
    ->addAttributeToSelect('*') //Select everything from the table
    ->addUrlRewrite(); //Generate nice URLs

//$conn = Mage::getSingleton('core/resource')->getConnection('core_read');
$j = 0;
//echo "<pre>";

foreach($collection as $product) {
    $productarray = array();
    $emptyoptions = array();
//	var_dump($product);
    echo '<h1>Product: ' . $product->getName() . '</h1>';
    foreach ($map as $fieldname => $info) {
        if($product->getData($fieldname) == '') {
            $emptyoptions[] = $fieldname;
        } else {
            switch ($info['type']) {
                case 'imagefile':
                    if (file_exists('../media/catalog/product' . $product->getData($fieldname))) {
                        echo $info['description'] . ' (Typ: ' . $info['type'] . '): <img style="max-height: 100px; max-width: 100px" src="https://' . $_SERVER['SERVER_NAME'] . '/media/catalog/product' . $product->getData($fieldname) . '"><br>';
                    } else {
                        echo $info['description'] . ' (Typ: ' . $info['type'] . '): IMAGE NOT FOUND!<br>';
                    }
                    break;
                case 'html':
                    echo $info['description'] . ' (Typ: ' . $info['type'] . '):<br><hr>' . htmlentities($product->getData($fieldname)) . '<hr><br>';
                    break;
                case 'int':
                    $value = (int) $product->getData($fieldname);
                    if(array_key_exists('value_explanation', $info)) {
                        echo $info['description'] . ' (Typ: ' . $info['type'] . '): ' . $value . ' (' . $info['value_explanation'][$value] . ')<br>';
                    } else {
                        echo $info['description'] . ' (Typ: ' . $info['type'] . '): ' . $value . '<br>';
                    }
                    break;
                default:
                    echo $info['description'] . ' (Typ: ' . $info['type'] . '): ' . strip_tags($product->getData($fieldname)) . '<br>';
            }
        }
    }
    echo 'Following Fields are empty: ';
    foreach ($emptyoptions as $emptyoption) {
        echo $emptyoption . ' ("' . $map[$emptyoption]['description'] . '", Typ: ' . $map[$emptyoption]['type'] . '); ';
    }
    echo '<br>';
//    echo 'Dateiname Ticketdesign: ' . htmlentities($product->getData('customticketdesign ')) . '<br>';
//    echo 'Anfahrt: ' . htmlentities($product->getData('anfahrt')) . '<br>';
//    echo 'Anfahrt: ' . htmlentities($product->getData('anfahrt')) . '<br>';
//    echo 'Anfahrt: ' . htmlentities($product->getData('anfahrt')) . '<br>';
//    if(is_object($product)) {
    $options = Mage::getModel('catalog/product_option')->getProductOptionCollection($product);
    if(is_object($options)) {
        $options_seen = false;
        $i = 1;
        foreach($options as $option) {
            if(!$options_seen) {
                echo "Following Options found:<br><pre>";
            }
            $options_seen = true;
            echo $i . '.  Name: ' . $option->getDefaultTitle() . '<br>';
            echo '    Type: ' . $option->getType() . '<br>';
            echo '    Price: ' . ($option->getPrice() ? $option->getPrice() : '0.00') . '<br>';

            $i++;
            $optionvalues_seen = false;
            foreach($option->getValues() as $value) {
                if(!$optionvalues_seen) {
                    echo '    Option values (Format: "Name: Price"):<br>';
                }
                $optionvalues_seen = true;
                echo '        - ' . $value->getTitle() . ': ' . $value->getPrice() . '<br>';
            }
        }
        if(!$options_seen) {
            echo 'No Options found!' . '<br>';
        } else {
            echo '</pre>';
        }
    }
//    }
//    if($j >= 10) {
//        break;
//    }
    $j++;
    echo '<br><br><br>';
}
//echo "</pre>";