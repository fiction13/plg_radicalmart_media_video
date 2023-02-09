<?php
/*
 * @package   plg_radicalmart_media_video
 * @version   __DEPLOY_VERSION__
 * @author    Dmitriy Vasyukov - https://fictionlabs.ru
 * @copyright Copyright (c) 2023 Fictionlabs. All rights reserved.
 * @license   GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 * @link      https://fictionlabs.ru/
 */

\defined('_JEXEC') or die;

extract($displayData);

/**
 * Layout variables
 * -----------------
 *
 * @var  object $item     Media item.
 * @var  array  $type     Media item type
 * @var  object $product  Product object.
 * @var object  $category Product category object.
 *
 */

?>
<div class="bg-white h-100 d-flex justify-content-center align-items-center text-center p-1">
	<video class="mw-100 mh-100 object-fit-contain" src="/<?php echo $item->src; ?>" autoplay loop muted></video>
</div>