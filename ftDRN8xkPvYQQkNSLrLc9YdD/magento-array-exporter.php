<?php
//include_once("inc/xlsxwriter.class.php");
require_once ("../app/Mage.php");
require_once ("Period.php");
//$app = Mage::app('default');

date_default_timezone_set('Europe/Berlin');
use League\Period\Period;

//$app = Mage::app('default');
header("Content-Type: application/json", true);
//header('Content-Disposition: attachment; filename="export.json"');

Mage::app('default');
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
Mage::setIsDeveloperMode(true);

//$products = Mage::getModel("catalog/product")->getCollection();

$event_types = array(
    'single_event' => 1,
    'super_event' => 2,
    'sub_event' => 3
);

$map = array(
    'anfahrt' => array(
        'description' => 'Anfahrt',
        'db_field' => 'directions',
        'type' => 'html',
        'import_for_event_type' => array(1, 2, 3)
    ),
    'customticketdesign' => array(
        'description' => 'Dateiname Ticketdesign',
        'db_field' => 'ticketdesign',
        'type' => 'null_or_non-emtpy_string',
        'import_for_event_type' => array(1, 3)
    ),
    'description' => array(
        'description' => 'Beschreibung',
        'db_field' => 'info',
        'type' => 'html',
        'db_type' => 'text',
        'import_for_event_type' => array(1, 2, 3)
    ),
    'entwurfsid' => array(
        'description' => 'EntwurfsID',
        'db_field' => 'drafts_id',
        'type' => 'int_force_null',
        'import_for_event_type' => array(1, 3)
    ),
    'eventenddatum' => array(
        'description' => 'Event-Enddatum',
        'db_field' => 'end',
        'type' => 'date',
        'import_for_event_type' => array(1, 2, 3)
    ),
    'eventgruppe' => array(
        'description' => 'Event-Gruppe',
        'db_field' => 'group',
        'type' => 'string',
        'import_for_event_type' => array()
    ),
    'eventgruppenbezeichnung' => array(
        'description' => 'Event-Gruppenbezeichnung',
        'db_field' => 'name_in_select',
//            array(
//            'type' => 'subordinated',
//            'event' => 'name'
//        ),
        'type' => 'string',
        'import_for_event_type' => array(3)
    ),
    'eventgruppenreihenfolge' => array(
        'description' => 'Event-Gruppenreihenfolge',
        'db_field' => 'order_in_select',
        'type' => 'int_not_null',
        'import_for_event_type' => array(3)
    ),
    'eventstartdatum' => array(
        'description' => 'Event-Startdatum',
        'db_field' => 'start',
        'type' => 'date',
        'import_for_event_type' => array(1, 2, 3)
    ),
//    'gallery' => array(
//        'description' => 'Image Gallery',
//        'db_field' => '',
//        'type' => 'imagefile'
//    ),
    'globaleeventid' => array(
        'description' => 'Globale Event ID',
        'db_field' => 'globalid',
        'type' => 'string',
        'import_for_event_type' => array(1, 3)
    ),
    'globaleveranstalterid' => array(
        'description' => 'Veranstalter ID',
        'db_field' => 'hosts_globalid',
//array(
//            'type' => 'foreign',
//            'host' => 'globalid'
//        ),
        'type' => 'string',
        'import_for_event_type' => array(1, 2, 3)
    ),
    'image' => array(
        'description' => 'Base Image',
        'db_field' => 'image_url',
        'type' => 'imagefile',
        'import_for_event_type' => array(1, 2, 3)
    ),
    'mdvberechtigung' => array(
        'description' => 'MDV-Fahrtberechtigung',
        'db_field' => 'oepnv',
        'type' => 'custom',
        'value_explanation' => array(
            'export_explanation' => true,
            0 => 'normal',
            1 => 'mdv210'
        ),
        'import_for_event_type' => array(1, 2, 3)
    ),
    'name' => array(
        'description' => 'Name',
        'db_field' => 'name',
        'type' => 'string',
        'import_for_event_type' => array(1, 3)
    ),
    'originalinstanzid' => array(
        'description' => 'Orginal Instanz ID',
        'db_field' => 'magento_instance',
        'type' => 'string',
        'import_for_event_type' => array(1, 3)
    ),
    'price' => array(
        'description' => 'Price',
        'db_field' => 'baseprice',
        'type' => 'decimal',
        'import_for_event_type' => array(1, 3)
    ),
    'price_view' => array(
        'description' => 'Price View',
        'db_field' => false,
        'type' => 'string'
    ),
    'short_description' => array(
        'description' => 'Kurzbeschreibung',
        'db_field' => 'slogan',
        'type' => 'string',
        'import_for_event_type' => array(1, 2, 3)
    ),
    'sku' => array(
        'description' => 'SKU',
        'db_field' => 'magento_sku',
        'type' => 'string',
        'import_for_event_type' => array(1, 3)
    ),
    'small_image' => array(
        'description' => 'Small Image',
        'db_field' => false,
        'type' => 'imagefile'
    ),
    'special_from_date' => array(
        'description' => 'Special Price From Date',
        'db_field' => false,
        'type' => 'date'
    ),
    'special_price' => array(
        'description' => 'Special Price',
        'db_field' => false,
        'type' => 'decimal'
    ),
    'special_to_date' => array(
        'description' => 'Special Price To Date',
        'db_field' => false,
        'type' => 'date'
    ),
    'status' => array(
        'description' => 'Status',
        'db_field' => 'magento_status',
        'type' => 'custom',
        'value_explanation' => array (
            'export_explanation' => false,
//            0 => 'Undefiniert',
            1 => 'Aktiviert',
            2 => 'Deaktiviert'
        ),
        'import_for_event_type' => array(1, 3)
    ),
    'tax_class_id' => array(
        'description' => 'Tax Class',
        'db_field' => 'tax',
        'type' => 'custom',
        'value_explanation' => array (
            'export_explanation' => true,
            0 => 0, //'keine',
            1 => 19, //'Vollbesteuerte Artikel',
            2 => 7, // 'Ermäßigtbesteuerte Artikel',
            8 => 16, //'Vollbesteuerter Versand',
            9 => 5 //'Ermäßigtbesteuerter Versand'
        ),
        'import_for_event_type' => array(1, 3)
    ),
    'thumbnail' => array(
        'description' => 'Thumbnail',
        'db_field' => false,
        'type' => 'imagefile'
    ),
    'ticketinformationen' => array(
        'description' => 'Ticketinformationen',
        'db_field' => 'ticket_info',
        'type' => 'html',
        'import_for_event_type' => array(1, 2, 3)
    ),
    'tiventsproduktstatus' => array(
        'description' => 'tivents Produkt Status',
        'db_field' => 'status',
        'type' => 'custom',
        'value_explanation' => array (
            'export_explanation' => false,
            100 => 'im Entwurf',
            200 => 'wird von tivents geprüft',
            201 => 'vom Veranstalter zurückgezogen',
            202 => 'wurde von tivents abgelehnt',
            300 => 'wird vom Veranstalter nachbearbeitet',
            301 => 'wird von tivents nachbearbeitet',
            400 => 'im Verkauf',
            401 => 'im Verkauf (nur Online)',
            402 => 'im Verkauf (nur Online & ausgewählter VVK-Stellen)',
            500 => 'Verkauf beendet',
            501 => 'vom Veranstalter aus dem Verkauf genommen',
            502 => 'Veranstaltung ist ausverkauft',
            503 => 'Veranstaltung wurde vom Veranstalter abgesagt',
            600 => 'beendet und abgerechnet'
        ),
        'import_for_event_type' => array(1, 3)
    ),
    'url_key' => array(
        'description' => 'URL Key',
        'db_field' => 'magento_url_key',
        'type' => 'string',
        'import_for_event_type' => array(1, 3)
    ),
    'ursprungslager' => array(
        'description' => 'Ursprungsanzahl',
        'db_field' => 'quota',
        'type' => 'int',
        'import_for_event_type' => array(1, 2, 3)
    ),
    'veranstaltungsort' => array(
        'description' => 'Veranstaltungsort',
        'db_field' => 'place',
        'type' => 'string',
        'import_for_event_type' => array(1, 2, 3)
    ),
    'visibility' => array(
        'description' => 'Visibility',
        'db_field' => 'magento_visibility',
        'type' => 'custom',
        'value_explanation' => array (
            'export_explanation' => false,
            1 => 'Einzeln nicht sichtbar',
            2 => 'Katalog',
            3 => 'Suche',
            4 => 'Katalog, Suche'
        ),
        'import_for_event_type' => array(1, 3)
    ),
    'ticketinfo_short' => array(
        'description' => 'Ticketkurzinfo',
        'db_field' => 'ticketinfo_short',
        'type' => 'string',
        'import_for_event_type' => array(1, 3)
    ),
    'product_type' => array(
        'description' => 'Produkttyp',
        'db_field' => 'product_type',
        'type' => 'int',
        'import_for_event_type' => array(1, 2, 3)
    ),

    'product_image_url' => array(
        'description' => 'Product Image CDN ',
        'db_field' => 'product_image_url',
        'type' => 'cdn_url',
        'import_for_event_type' => array(1, 3)
    ),
);



$collection = Mage::getModel('catalog/product')
    ->getCollection()
    ->addAttributeToFilter('status', array('eq' => Mage_Catalog_Model_Product_Status::STATUS_ENABLED))
    ->addAttributeToSelect('*') //Select everything from the table
    ->addUrlRewrite(); //Generate nice URLs

//$conn = Mage::getSingleton('core/resource')->getConnection('core_read');
$j = 0;
$productarray = array();
$hostarray = array();
$eventgrouparray = array();
$grouphelperarray = array();
$nexthostid = 1;
$skippedproductsarray = array();

foreach($collection as $product) {
//    echo 'Product: ' . $j . "\r\n";
    $globalid = $product->getData('globaleeventid');
    if($product->getAttributeSetId() == 12) {
        $skippedproductsarray[] = array(
            'globalid' => $globalid,
            'name' => $product->getData('name'),
            'start' => $product->getData('eventstartdatum'),
            'magento_sku' => $product->getData('sku'),
            'magento_status' => $product->getData('status'),
            'tivents_status' => $product->getData('tiventsproduktstatus'),
            'reason' => 'Not an Event but Saale Bulls Streaming Code.');
        continue;
    }
    if(empty($globalid)) {
        $skippedproductsarray[] = array(
            'globalid' => $globalid,
            'name' => $product->getData('name'),
            'start' => $product->getData('eventstartdatum'),
            'magento_sku' => $product->getData('sku'),
            'magento_status' => $product->getData('status'),
            'tivents_status' => $product->getData('tiventsproduktstatus'),
            'reason' => 'Event Global ID is empty.');
        continue;
    }
    if($globalid == 'xxkl') {
        $skippedproductsarray[] = array(
            'globalid' => $globalid,
            'name' => $product->getData('name'),
            'start' => $product->getData('eventstartdatum'),
            'magento_sku' => $product->getData('sku'),
            'magento_status' => $product->getData('status'),
            'tivents_status' => $product->getData('tiventsproduktstatus'),
            'reason' => 'Not an Event but Saale Bulls Streaming Code.');
        continue;
    }
    if(array_key_exists($globalid, $productarray)) {
        $skippedproductsarray[] = array(
            'globalid' => $globalid,
            'name' => $product->getData('name'),
            'start' => $product->getData('eventstartdatum'),
            'magento_sku' => $product->getData('sku'),
            'magento_status' => $product->getData('status'),
            'tivents_status' => $product->getData('tiventsproduktstatus'),
            'reason' => 'Event Global ID already in use.');
        continue;
    }
//    $globalid = $j;
    if(!array_key_exists($product->getData('globaleveranstalterid'), $hostarray)) {
        $hostarray[$product->getData('globaleveranstalterid')] = array('id' => $nexthostid, 'globalid' => $product->getData('globaleveranstalterid'));
        $nexthostid++;
    }
    foreach ($map as $fieldname => $info) {
        $db_fieldname =  $info['db_field'];
        if($db_fieldname === false) {
            continue;
        } elseif (is_array($db_fieldname)) {
            continue;
        }
//        echo "DB-Field: " . $db_fieldname . "\r\n";
        $productarray[$globalid][$db_fieldname] = $info;
        $value = $product->getData($fieldname);
//        if($value === '') {
//            $productarray[$globalid][$db_fieldname]['value'] = null;
//        } else {
        switch ($info['type']) {
            case 'imagefile':
                if (file_exists('../media/catalog/product' . $value)) {
                    $productarray[$globalid][$db_fieldname]['value'] = 'https://' . $_SERVER['SERVER_NAME'] . '/media/catalog/product' . $value;
                } else {
                    $productarray[$globalid][$db_fieldname]['value'] = null;
                }
                break;
            case 'html':
//                    $productarray[$globalid][$db_fieldname]['value'] = htmlentities($value);
                $productarray[$globalid][$db_fieldname]['value'] = $value;
                break;
            case 'custom':
//                    if(is_null($value)) {
//                        $productarray[$globalid][$db_fieldname]['value'] = null;
//                        break;
//                    }
                $value = (int) $value;
                $productarray[$globalid][$db_fieldname]['original_value'] = (int) $value;
//                    echo $productarray[$globalid][$db_fieldname]['value'];
                if(array_key_exists('value_explanation', $info)) {
                    if (array_key_exists($value, $info['value_explanation'])) {
//                        echo $productarray[$globalid][$db_fieldname]['value'];
//                            $productarray[$globalid][$db_fieldname]['value_explanation'] = $info['value_explanation'][$productarray[$globalid][$db_fieldname]['value']];
                        if(array_key_exists('export_explanation', $info['value_explanation']) && $info['value_explanation']['export_explanation']) {
                            $productarray[$globalid][$db_fieldname]['value'] = $info['value_explanation'][$value];
                        } else {
                            $productarray[$globalid][$db_fieldname]['value'] = $value;
                        }
                    } else {
//                            $productarray[$globalid][$db_fieldname]['value_explanation'] = 'unknown';
//                            $productarray[$globalid][$db_fieldname]['value'] = 'Warning: unknown value: ' . $value;
                        $skippedproductsarray[] = array(
                            'globalid' => $globalid,
                            'name' => $product->getData('name'),
                            'start' => $product->getData('eventstartdatum'),
                            'magento_sku' => $product->getData('sku'),
                            'magento_status' => $product->getData('status'),
                            'tivents_status' => $product->getData('tiventsproduktstatus'),
                            'reason' => 'Unknown value: "' . $value .'" in field "' . $db_fieldname . '"');
                        continue 3;
                    }
                } else {
                    $productarray[$globalid][$db_fieldname]['value'] = (int) $value;
                }
                break;
            /** @noinspection PhpMissingBreakStatementInspection */
            case 'int':
                if(is_null($value)) {
                    $productarray[$globalid][$db_fieldname]['value'] = null;
                    break;
                }
            // no break
            case 'int_not_null':
                $productarray[$globalid][$db_fieldname]['value'] = (int) $value;
                break;
            case 'int_force_null':
                $value = (int) $value;
                if($value == 0) {
                    $productarray[$globalid][$db_fieldname]['value'] = null;
                } else {
                    $productarray[$globalid][$db_fieldname]['value'] = $value;
                }
                break;
            case 'date':
                $productarray[$globalid][$db_fieldname]['value'] = $value;
                break;
            case 'cdn_url':
                if($value != null) {
                    $productarray[$globalid]['image_url']['value'] = $value;
                }
                break;
            /** @noinspection PhpMissingBreakStatementInspection */
            case 'null_or_non-emtpy_string':
                if(empty($value)) {
                    $productarray[$globalid][$db_fieldname]['value'] = null;
                    break;
                }
            // no break
            default:
                if($db_fieldname == 'baseprice' && $product->getData('special_price') > 0) {
                    $now = date("Y-m-d");
                    $from = date("Y-m-d", strtotime($product->getData('special_from_date')));
                    $to = date("Y-m-d", strtotime($product->getData('special_to_date')));
                    if($from <= $now && $now <= $to) {
                        $productarray[$globalid][$db_fieldname]['value'] = strip_tags($product->getData('special_price'));
                    } else {
                        $productarray[$globalid][$db_fieldname]['value'] = strip_tags($value);
                    }
                } else {
                    $productarray[$globalid][$db_fieldname]['value'] = strip_tags($value);
                }
        }
//        }
    }

    $options = Mage::getModel('catalog/product_option')->getProductOptionCollection($product);
    if(is_object($options)) {
        $options_seen = false;
        $i = 1;
        foreach($options as $option) {
            $constraint = array();
//            if(!$options_seen) {
//                $productarray[$globalid]['options'] = array();
//                $options_seen = true;
//            }

            $constraint['name'] = $option->getDefaultTitle();
            $constraint['type'] = $option->getType();
            $constraint['price'] = ($option->getPrice() ? $option->getPrice() : '0.00');

            $optionvalues_seen = false;
            $optionvalues_change_price = false;
            $k = 0;
            foreach($option->getValues() as $value) {
//                if(!$optionvalues_seen) {
//                    $productarray[$globalid]['options'][$i]['values'][$k] = array();
//                    $optionvalues_seen = true;
//                }
                $constraint['values'][$k]['title'] = $value->getTitle();
                $constraint['values'][$k]['price'] = $value->getPrice();
                if($constraint['values'][$k]['price'] != 0) {
                    $optionvalues_change_price = true;
                }
                $k++;
            }

            switch($constraint['type']) {
                case 'checkbox':
//                    if($optionvalues_change_price) {
                    $productarray[$globalid]['constraints'][$i]['constraint'] = 'option';
                    $productarray[$globalid]['constraints'][$i]['viewtype'] = 'select-quantity';
                    $productarray[$globalid]['constraints'][$i]['description'] = 'In welcher Anzahl sind Tickettypen jeweils gewünscht?';
                    $productarray[$globalid]['constraints'][$i]['name'] = $constraint['name'];
                    $productarray[$globalid]['constraints'][$i]['formfields'] = array (
                        array("id" => 5, "name" => "Normal", "pricechange" => 0, "value" => 0),
                        array("id" => 6, "name" => $constraint['values'][0]['title'], "pricechange" => $constraint['values'][0]['price'], "value" => 0)
                    );
//                    } else {
//                    }
                    break;
                case 'radio': //12
                case 'drop_down':
                    $productarray[$globalid]['constraints'][$i]['constraint'] = 'option';
                    $productarray[$globalid]['constraints'][$i]['viewtype'] = 'select-quantity';
                    $productarray[$globalid]['constraints'][$i]['description'] = 'In welcher Anzahl sind Tickettypen jeweils gewünscht?';
                    $productarray[$globalid]['constraints'][$i]['name'] = $constraint['name'];
                    foreach($constraint['values'] as $key => $value) {
                        $productarray[$globalid]['constraints'][$i]['formfields'][] =
                            array("id" => $key, "name" => $value['title'], "pricechange" => $value['price'], "value" => 0);
                    }
                    break;
                case 'field': //89
                    $productarray[$globalid]['constraints']['form']['constraint'] = 'name';
                    $productarray[$globalid]['constraints']['form']['viewtype'] = 'form';
                    $productarray[$globalid]['constraints']['form']['description'] = 'Bitte machen Sie noch folgende Angaben:';
                    $productarray[$globalid]['constraints']['form']['name'] = 'Angaben';
                    $productarray[$globalid]['constraints']['form']['formfields'][] =
                        array("id" => $i, "name" => $constraint['name'], "value" => "", "required" => true, "type" => "text");
                    break;
                default:
            }



//            $productarray[$globalid]['constraints'][$i] = $constraint;
            $i++;
        }
    }

    if($productarray[$globalid]['group']['value'] != '') {
        $group = $productarray[$globalid]['group']['value'];
        $event_type = $event_types['super_event'];
        $productarray[$globalid]['name_in_select']['generated_from_date'] = false;

        if($productarray[$globalid]['name_in_select']['value'] == '') {
            $productarray[$globalid]['name_in_select']['value'] = getNiceDate(new DateTimeImmutable($productarray[$globalid]['start']['value']), new DateTimeImmutable($productarray[$globalid]['end']['value']));
            $productarray[$globalid]['name_in_select']['generated_from_date'] = true;
        }

        if(!array_key_exists($group, $eventgrouparray)) {
            $grouphelperarray[$group]['periodHelper'] = $period = new Period($productarray[$globalid]['start']['value'],$productarray[$globalid]['end']['value']);
            $eventgrouparray[$group]['globalid'] = 'super-' . $productarray[$globalid]['globalid']['value'];
            if (preg_match("/^(?P<date>\d\d-\d\d-\d\d\d\d)-(?P<rest>.*)$/", $group, $name_parts) === 1) {
                $date = $name_parts['date'];
                $nice_groupname = $name_parts['rest'];
                $nice_groupname = str_replace("-", " ", $nice_groupname);
                $nice_groupname = mb_convert_case($nice_groupname, MB_CASE_TITLE, "UTF-8");
                $nice_groupname = str_replace("Mec", "MEC", $nice_groupname);
                $nice_groupname = str_replace("Vs", "vs.", $nice_groupname);
                $nice_groupname = str_replace(" Stehplatz", "", $nice_groupname);
                $nice_groupname = str_replace(" Sitzplatz", "vs.", $nice_groupname);
            } else {
                $nice_groupname = $group;
            }
            $eventgrouparray[$group]['name'] = $nice_groupname;
            if($productarray[$globalid]['status']['value'] == 400){
                $eventgrouparray[$group]['count'] = 1;
                $eventgrouparray[$group]['status'] = 400;
            } elseif(in_array($productarray[$globalid]['status']['value'], array(500, 501, 502, 503, 600))) {
                $eventgrouparray[$group]['count'] = 1;
                $eventgrouparray[$group]['status'] = 500;
            } else {
                $eventgrouparray[$group]['count'] = 0;
                $eventgrouparray[$group]['status'] = 301;
            }
            $eventgrouparray[$group]['quota'] = $productarray[$globalid]['quota']['value'];
            $eventgrouparray[$group]['oepnv'] = $productarray[$globalid]['oepnv']['value'];
            if(($globalid == 's2fb') || ($globalid == '6sxg')) {
                $eventgrouparray[$group]['in_eventlist'] = false;
            } else {
                $eventgrouparray[$group]['in_eventlist'] = true;
            }
            $eventgrouparray[$group]['type'] = $event_type;
            $eventgrouparray[$group]['start'] = $productarray[$globalid]['start']['value'];
            $eventgrouparray[$group]['end'] = $productarray[$globalid]['end']['value'];
            $eventgrouparray[$group]['place'] = $productarray[$globalid]['place']['value'];
            $eventgrouparray[$group]['hosts_globalid'] = $productarray[$globalid]['hosts_globalid']['value'];

            $eventgrouparray[$group]['end_of_sales'] = $productarray[$globalid]['end']['value'];
            $eventgrouparray[$group]['constraints'] = array();
            $grouphelperarray[$group]['differentStarts'] = false;
            $grouphelperarray[$group]['start'] = $productarray[$globalid]['start']['value'];

            foreach($productarray[$globalid] as $fieldname => $info) {
                if(!array_key_exists('import_for_event_type', $info) || !in_array($event_type, $info['import_for_event_type'])) {
                    continue;
                }
                $eventgrouparray[$globalid][$fieldname] = $info['value'];
            }
        } else {
            if($grouphelperarray[$group]['periodHelper']->isBefore($productarray[$globalid]['end']['value'])) {
                $grouphelperarray[$group]['periodHelper'] = $grouphelperarray[$group]['periodHelper']->endingOn($productarray[$globalid]['end']['value']);
            }
            if($grouphelperarray[$group]['periodHelper']->isAfter($productarray[$globalid]['start']['value'])) {
                $grouphelperarray[$group]['periodHelper'] = $grouphelperarray[$group]['periodHelper']->startingOn($productarray[$globalid]['start']['value']);
            }
            if($grouphelperarray[$group]['start'] != $productarray[$globalid]['start']['value']) {
                $grouphelperarray[$group]['differentStarts'] = true;
            }
//            if($productarray[$globalid]['name_in_select']['generated_from_date']) {
//                $grouphelperarray[$group]['name_in_select_generated_from_date'] = true;
//            }
            if($productarray[$globalid]['status']['value'] == 400 && $eventgrouparray[$group]['status'] != 400){
                $eventgrouparray[$group]['count']++;
                $eventgrouparray[$group]['status'] = 400;
            } elseif(in_array($productarray[$globalid]['status']['value'], array(400, 500, 501, 502, 503, 600))) {
                $eventgrouparray[$group]['count']++;
            } else {
                $event_type = $event_types['sub_event'];
                foreach($productarray[$globalid] as $fieldname => $info) {
                    if(!array_key_exists('import_for_event_type', $info) || !in_array($event_type, $info['import_for_event_type'])) {
                        continue;
                    }
                    $subeventarray[$fieldname] = $info['value'];
                }
                if(array_key_exists('constraints', $productarray[$globalid])) {
                    $subeventarray['constraints'] = $productarray[$globalid]['constraints'];
                } else {
                    $subeventarray['constraints'] = array();
                }
                if(is_null($productarray[$globalid]['start']['value'])) {
                    $subeventarray['date'] = 'Gutschein';
                } else {
                    $subeventarray['date'] = getNiceDate(new DateTimeImmutable($productarray[$globalid]['start']['value']), new DateTimeImmutable($productarray[$globalid]['end']['value']));
                }
                if(($globalid == 's2fb') || ($globalid == '6sxg')) {
                    $subeventarray['in_eventlist'] = false;
                } else {
                    $subeventarray['in_eventlist'] = true;
                }
                $subeventarray['type'] = $event_type;
                $subeventarray['end_of_sales'] = $productarray[$globalid]['end']['value'];
                $eventgrouparray[$globalid] = $subeventarray;
                continue;
            }

            $eventgrouparray[$group]['quota'] += $productarray[$globalid]['quota']['value'];
            if($eventgrouparray[$group]['start'] > $productarray[$globalid]['start']['value']) {
                $eventgrouparray[$group]['start'] = $productarray[$globalid]['start']['value'];
            }
            if($eventgrouparray[$group]['end_of_sales'] < $productarray[$globalid]['end']['value']) {
                $eventgrouparray[$group]['end_of_sales'] = $productarray[$globalid]['end']['value'];
            }
            if($eventgrouparray[$group]['end'] < $productarray[$globalid]['end']['value']) {
                $eventgrouparray[$group]['end'] = $productarray[$globalid]['end']['value'];
            }
        }
        $event_type = $event_types['sub_event'];
        $eventgrouparray[$group]['events'][$globalid] = array(
            'globalid' => $globalid,
            'name_in_select' => $productarray[$globalid]['name_in_select']['value'],
            'baseprice' => $productarray[$globalid]['baseprice']['value'],
            'quota' => $productarray[$globalid]['quota']['value'],
            'end_of_sales' => $productarray[$globalid]['end']['value'],
            'status' => $productarray[$globalid]['status']['value'],
            'order_in_select' => $productarray[$globalid]['order_in_select']['value']
        );

        foreach($productarray[$globalid] as $fieldname => $info) {
            if(!array_key_exists('import_for_event_type', $info) || !in_array($event_type, $info['import_for_event_type'])) {
                continue;
            }
            $subeventarray[$fieldname] = $info['value'];
        }
        if(array_key_exists('constraints', $productarray[$globalid])) {
            $subeventarray['constraints'] = $productarray[$globalid]['constraints'];
        } else {
            $subeventarray['constraints'] = array();
        }
        if(is_null($productarray[$globalid]['start']['value'])) {
            $subeventarray['date'] = 'Gutschein';
        } else {
            $subeventarray['date'] = getNiceDate(new DateTimeImmutable($productarray[$globalid]['start']['value']), new DateTimeImmutable($productarray[$globalid]['end']['value']));
        }
        if(($globalid == 's2fb') || ($globalid == '6sxg')) {
            $subeventarray['in_eventlist'] = false;
        } else {
            $subeventarray['in_eventlist'] = true;
        }
        $subeventarray['type'] = $event_type;
        $subeventarray['end_of_sales'] = $productarray[$globalid]['end']['value'];
        $eventgrouparray[$globalid] = $subeventarray;

    } else {
        $event_type = $event_types['single_event'];
        foreach($productarray[$globalid] as $fieldname => $info) {
            if(!array_key_exists('import_for_event_type', $info) || !in_array($event_type, $info['import_for_event_type'])) {
                continue;
            }
            $eventgrouparray[$globalid][$fieldname] = $info['value'];
        }
        if(array_key_exists('constraints', $productarray[$globalid])) {
            $eventgrouparray[$globalid]['constraints'] = $productarray[$globalid]['constraints'];
        } else {
            $subeventarray['constraints'] = null;
        }
        if(is_null($productarray[$globalid]['start']['value'])) {
            $eventgrouparray[$globalid]['date'] = 'Gutschein';
        } else {
            $eventgrouparray[$globalid]['date'] = getNiceDate(new DateTimeImmutable($productarray[$globalid]['start']['value']), new DateTimeImmutable($productarray[$globalid]['end']['value']));
        }

        $eventgrouparray[$globalid]['type'] = $event_type;
        if(($globalid == 's2fb') || ($globalid == '6sxg')) {
            $eventgrouparray[$globalid]['in_eventlist'] = false;
        } else {
            $eventgrouparray[$globalid]['in_eventlist'] = true;
        }
        $eventgrouparray[$globalid]['end_of_sales'] = $productarray[$globalid]['end']['value'];
    }

    $j++;
}

foreach ($grouphelperarray as $group => $grouphelper) {
    if(is_null($grouphelper['start'])) {
        usort($eventgrouparray[$group]['events'], function ($a, $b) {
            return $a['order_in_select'] - $b['order_in_select'];
        });
    } else {
        usort($eventgrouparray[$group]['events'], function ($a, $b) {
            return strcmp($a['end_of_sales'], $b['end_of_sales']);
        });
    }
    $startDateTimeImmutable = $grouphelper['periodHelper']->getStartDate();
    $endDateTimeImmutable = $grouphelper['periodHelper']->getEndDate();
    if($grouphelper['differentStarts']) {
        $eventgrouparray[$group]['date'] = getNiceDate($startDateTimeImmutable, $endDateTimeImmutable, $eventgrouparray[$group]['count']);
    } else {
        if(is_null($grouphelper['start'])) {
            $eventgrouparray[$group]['date'] = 'Gutscheine';
        } else {
            $eventgrouparray[$group]['date'] = getNiceDate($startDateTimeImmutable, $endDateTimeImmutable);
        }
    }
}

$finalarray = array('hosts' => array_values($hostarray), 'events' => array_values($eventgrouparray), 'skipped' => array_values($skippedproductsarray));
echo json_encode($finalarray);


function getNiceDate($startDateTimeImmutable, $endDateTimeImmutable, $count = 1) {
    setlocale(LC_TIME, 'de_DE@euro', 'de_DE', 'deu_deu');

    if($count == 1) {
        if ($startDateTimeImmutable->format('d.m.Y') == $endDateTimeImmutable->format('d.m.Y')) {
            $date = $startDateTimeImmutable->format('d.m.y \v\o\n H:i') . ' bis ' . $endDateTimeImmutable->format('H:i') . ' Uhr';
        } else {

            if ($startDateTimeImmutable->format('m.Y') == $endDateTimeImmutable->format('m.Y')) {

                $date = $startDateTimeImmutable->format('d.') . ' bis ' . $endDateTimeImmutable->format('d.m.Y');
            } else {

                if ($startDateTimeImmutable->format('Y') == $endDateTimeImmutable->format('Y')) {
                    $date = $startDateTimeImmutable->format('d.m.') . ' bis ' . $endDateTimeImmutable->format('d.m.Y');
                } else {
                    $date = $startDateTimeImmutable->format('d.m.Y') . ' bis ' . $endDateTimeImmutable->format('d.m.Y');
                }
            }
        }
    } else {
        $prefix = $count . ' Events ';
        if ($startDateTimeImmutable->format('d.m.Y') == $endDateTimeImmutable->format('d.m.Y')) {
            $date = $prefix . 'am ' . $startDateTimeImmutable->format('d.m.y');
        } else {
            if ($startDateTimeImmutable->format('m.Y') == $endDateTimeImmutable->format('m.Y')) {

                $date = $prefix . 'im Zeitraum vom ' . $startDateTimeImmutable->format('d.') . ' bis ' . $endDateTimeImmutable->format('d.m.Y');
            } else {
                if ($startDateTimeImmutable->format('Y') == $endDateTimeImmutable->format('Y')) {
                    $date = $prefix . 'im Zeitraum vom ' . $startDateTimeImmutable->format('d.m.') . ' bis ' . $endDateTimeImmutable->format('d.m.Y');
                } else {
                    $date = $prefix . 'im Zeitraum vom ' . $startDateTimeImmutable->format('d.m.Y') . ' bis ' . $endDateTimeImmutable->format('d.m.Y');
                }
            }
        }
    }
    return $date;
}