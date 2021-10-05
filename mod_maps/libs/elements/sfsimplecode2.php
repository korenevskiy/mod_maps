<?php
/**

 **/
defined('_JEXEC') or die(':)');

 
// Подключение требуемых файлов
jimport('joomla.form.formfield');
/**
 * Создаем класс. Fieldname - имя типа
 */
class JFormFieldSfsimplecode2 extends JFormField
{
	/**
	 * @var $type	Имя типа
	 */
	protected $type = 'Sfsimplecode2';
	
	
	/**
	 * Метод, определяющий что будет выводить параметр
	 *
	 * @return	Результат вывода типа
	 */
	protected function getInput()
	{
	$module_id = intval(@$_REQUEST['id']);
	//делаем запрос на вывод котлет	
	$db = JFactory::getDBO();
	$query = $db->getQuery(true);
	   // теперь сделаем вывод материалов
	   $db->setQuery("SELECT * from #__modules WHERE id=$module_id");
	   $rows = $db->loadObjectList();	
	   foreach ($rows as $y=>$row) {
	   $id = $row->id; // id модуля  
		// ВЫВОД СЛОЖНЫХ ПАРАМЕТРОВ
		$params = json_decode($row->params); 
		$centermap = @$params->centermap;
		$scope = @$params->scope;
		$typemap_bool = @$params->typemap_bool;
		$bottom_bool = @$params->bottom_bool;
		$trafficControl = @$params->trafficControl;
		$wi = @$params->wi;
		$he = @$params->he;
		$iconmap = @$params->iconmap;
		$icon_wi = @$params->icon_wi;
		$icon_he = @$params->icon_he;
		$zn1 = @$params->zn1;
		$zn2 = @$params->zn2;		
		$list_templates = @$params->list_templates;		
$list_templates_json_decode = json_decode($list_templates);
//print_r($list_templates_json_decode);

// исправлено ---- 29 сетб 2016 года
$point=array();
$desc_explode =array();
//
if($list_templates_json_decode) {
foreach ($list_templates_json_decode->template as  $value)
   {
	$temp[]=$value; // выводим точки
   }
                                } // if $list_templates_json_decode
								
								else {
									$temp[0] = $centermap; // При первом запуске нет точек
								      }
if($list_templates_json_decode) {
foreach ($list_templates_json_decode->point as  $value)
   {
	$point[]=$value; // выводим флажки 
   }   
                                } // if $list_templates_json_decode
if($list_templates_json_decode) {   
foreach ($list_templates_json_decode->description as  $value)
   {

	$desc_explode[] = trim($value);// выводим описание    
   }
                                } // if $list_templates_json_decode
   
   $count = count($point); // сколько точек ?
// первый раз пока модуль не создан нужно прописать что нибудь.
if(!$centermap) {$centermap = "55.75399400, 37.62209300";}
if(!$scope) {$scope = "10";}
if(!$wi) {$wi = "740";}
if(!$he) {$he = "450";}
if(!$iconmap) {$iconmap = "modules/mod_maps2018/images/myIcon.png";}
if(!$icon_wi) {$icon_wi = "70";}
if(!$icon_he) {$icon_he = "70";}
if(!$zn1) {$zn1 = "-20";}
if(!$zn2) {$zn2 = "-70";}
// это $count - первый раз ее нет поэтому можно вывести
if(!$count) {$count = "1";}	
	 
	 
	   } // foreach 

$html ="";
$html.="<script src=\"https://api-maps.yandex.ru/2.1/?lang=ru_RU\" type=\"text/javascript\"></script>";

$html.="
<script type=\"text/javascript\">
var myMap;
ymaps.ready(init);
function init () {
    var myMap = new ymaps.Map(\"map2\", {
            center: [".$centermap."],
            zoom: ".$scope.",
			controls: ['zoomControl', ";
			

			 if($bottom_bool==1) {$html.="'searchControl',";} 
			 if($typemap_bool==1) {$html.="'typeSelector',";}
			 $html.="'fullscreenControl']   });";
			 

for($i=0;$i<$count;$i++)
{

  $html.="myPlacemark".$i." = new ymaps.Placemark([";
  if(isset($temp[$i])) {
	$html.= $temp[$i];  
	  }
  else {$html.=$centermap;} 
  $html.= "], {
  balloonContent: '"; 
    $desc = @explode("\n", $desc_explode[$i]);
	
	for($y=0;$y<count($desc);$y++) {$html.=$desc[$y];}
	
	$html.="'}, {
					
            iconLayout: 'default#image',
            iconImageHref: '";

		if ($point[$i]) {
			$img = '/images/' . $point[$i];
		} else {
			$img =  '/'.$iconmap;
		}


			$html.=$img."',
            iconImageSize: [".$icon_wi.", ".$icon_he."],
           iconImageOffset: [".$zn1.",".$zn2."]
        });";
		
} // конец for

if($trafficControl==1) $trafficControl="true"; else $trafficControl="false";
$html.="var trafficControl = new ymaps.control.TrafficControl({ state: {

            providerKey: 'traffic#actual',
            trafficShown: ".$trafficControl."
        }});
    myMap.controls.add(trafficControl);
    trafficControl.getProvider('traffic#actual').state.set('infoLayerShown', true);";  



$html.="    myMap.events.add('click', function (e) {
        if (!myMap.balloon.isOpen()) {
            var coords = e.get('coords');
            myMap.balloon.open(coords, {
                contentHeader:'Вы определили координаты точки!',
                contentBody:'<p></p>' +
                    '<p>Скопируйте координаты:&nbsp;&nbsp;&nbsp;<span class=\"bufer_date\" id=\"source\" ><strong>' + [
                    coords[0].toPrecision(6),
                    coords[1].toPrecision(6)
                    ].join(', ') + '</strong></span></p>',
                contentFooter:'<sup>Затем, нажмите \"Поставь точки на карте\"</sup>'
            });
        }
        else {
            myMap.balloon.close();
        }
    });";


$html.="myMap.geoObjects";
for($i=0;$i<$count;$i++)
{ 	
$html.=".add(myPlacemark".$i.")";
 } 
    

$wi = trim($wi); 
$mystring = $wi;
$findme   = '%';
$findme2   = 'px';
$pos_proc = strpos($mystring, $findme);
$pos_px = strpos($mystring, $findme2);

// Заметьте, что используется ===.  Использование == не даст верного результата
if (($pos_proc === false) and ($pos_px === false)) { $wi =  $wi."px";} else { $wi;} 
$he = trim($he); 
$mystring = $he;
$findme   = '%';
$findme2   = 'px';
$pos_proc = strpos($mystring, $findme);
$pos_px = strpos($mystring, $findme2);

// Заметьте, что используется ===.  Использование == не даст верного результата
if (($pos_proc === false) and ($pos_px === false)) { $he = $he."px";} else {  $he;} 	
	
	
	
$html.="}
</script>
<div id=\"map2\" style=\"width:".$wi."; height:".$he."\"></div>
"; 
		// ...
		// Какой-то код, в котором определяется что нужно выводить
		// ...
 
		return $html; 
	}
}
?>
