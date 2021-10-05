<?php
/**
 * API ������ ����
2018
 */
use Joomla\CMS\Factory as JFactory;

defined('_JEXEC') or die;

$list = json_decode($list_templates);

$template = $list->template;
$description = $list->description;
$point = $list->point;

//include ('/class.jbdump.php'); // ����������� jbdump
//jbdump($list); // �������


if(!$list ) {
    $template = [ '0' =>'55.75399400, 37.62209300'];
    $description = [ '0' =>'������'];
    $point = null;
}
$count = count($template); // ������� ����� ?
$doc = JFactory::getDocument();
$doc->addScript("https://api-maps.yandex.ru/2.1/?lang=ru_RU");
$rand = rand(1,1000);
?>
<div class="yandex_map" id="yandex_map<?php echo $rand;?>">
    <script type="text/javascript">
        var myMap;
        // ������� �������� API � ���������� DOM.
        ymaps.ready(init);

        function init () {
            var myMap = new ymaps.Map("map2<?php echo $rand;?>", {
                center: [<?php echo $centermap;?>],
                zoom: <?php echo $scope;?>,
                controls: [
                    <?php
                    echo $scrope_bool == 1 ? "'zoomControl'," : "";
                    echo $bottom_bool == 1 ? "'searchControl'," : "";
                    echo $typemap_bool == 1 ? "'typeSelector'," : "";
                    echo $scrope_bool == 1 ? "'zoomControl'," : "";
                    echo "'fullscreenControl'";
                    ?>
                ]
                //,type: "yandex#satellite" // �������� �������
            });
            <?php
            //for($i=0;$i<$count;$i++)
            foreach ($template as $i=>$temp)    {
            ?>
            // ������� ��������� � ����� ��������� "�����".
            myPlacemark<?php echo $i?> = new ymaps.Placemark([<?php echo $temp;?>], {
                // ��������.
                balloonContent: '<?php
                    $description_ = explode("\n", $description[$i]);
                    foreach ($description_ as $desc) {
                        echo $desc; // "��������" ������ ��� ����������� ����������� ����� HTML
                    }
                    ?>'
            }, {
                // ���������� ������� ������ ��� ������.
                iconLayout: 'default#image',
                // ��� ����������� ������ �����.
                //
                <?php
                if ($point[$i]) {
                    $img = JFactory::getApplication()->getDocument()->getBase() . 'images/' . $point[$i];
                } else {
                    $img = JFactory::getApplication()->getDocument()->getBase() . $iconmap;
                }  ?>
                iconImageHref: '<?php echo $img;?>',
                // ������� �����
                iconImageSize: [<?php echo $icon_wi;?>, <?php echo $icon_he;?>],
                // �������� ������ �������� ���� ������ ������������
                // � "�����" (����� ��������).
                iconImageOffset: [<?php echo $zn1;?>, <?php echo $zn2;?>]
            });

            <?php
            } // ����� foreach

            if($trafficControl == 1) {?>
            // �������� ������� ���������� "������".
            var trafficControl = new ymaps.control.TrafficControl({
                state: {
                    // ������������ ������ "������".
                    providerKey: 'traffic#actual',
                    // �������� ����� ���������� ������ �� �����.
                    trafficShown: true
                }
            });
            // ������� ������� �� �����.
            myMap.controls.add(trafficControl);
            // ������� ������ �� ��������� ������ "������" � ������� ����� ���������.
            trafficControl.getProvider('traffic#actual').state.set('infoLayerShown', true);
            // ��������� ��� ����� �� �����.
            <?php  } ?>
            <?php if($scaling==1) { ?>myMap.behaviors.disable('scrollZoom'); <?php } ?>
            myMap.geoObjects
            <?php
            for ($i = 0; $i < $count; $i++) {
                echo ' .add(myPlacemark' . $i . ') ';
            }
            ?>
        }
    </script>
    <div class="spb-webmaster" id="map2<?php echo $rand;?>" style="<?php
    
        //['px','mm','cm','pt','pc','em','rem','ex','ch','vw','vh','vmin','vmax','%',];
    $getUE = function($size){
        $size = trim($size);
        $ue = ['px','mm','cm','pt','pc','em','ex','ch','vw','vh','in','ax','%'];
        if(in_array(substr($size, -2), $ue)){
            return $size;
        }
        if(substr($size, -1) == '%'){
            return $size;
        }
        return $size.'px';
    };
    
    $wi = $getUE($wi); 
    echo " width:$wi;";
    
    $he = $getUE($he);
    echo " height:$he;";
    
    unset($getUE);?>"></div>
</div>
<div class="clear" style="clear:both"></div>
