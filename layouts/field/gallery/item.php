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

use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\Utilities\ArrayHelper;

extract($displayData);

/**
 * Layout variables
 * -----------------
 * @var   string $selector Current field selector.
 * @var   string $name     Name of the input field.
 * @var   array  $value    Value attribute of the field.
 * @var   bool   $template Is template.
 *
 */

if ($template)
{
	return LayoutHelper::render('plugins.radicalmart_media.video.field.gallery.template', $displayData);
}

if (empty($value['src']))
{
	return;
}

$fields = [
	[
		'type'  => 'hidden',
		'name'  => $name . '[type]',
		'value' => $value['type'],
	],
	[
		'type'  => 'hidden',
		'name'  => $name . '[src]',
		'value' => $value['src'],
	]
]
?>
<div class="col col-md-6 col-lg-3 mb-3" radicalmart-field-gallery="item"
	 data-selector="<?php echo $selector; ?>">
	<div class="card">
		<div class="preview border border-bottom-0 bg-light d-flex align-items-center justify-content-center p-2  text-center"
			 style="height: 250px;">
            <video radicalmart-field-gallery="preview" class="mw-100 mh-100 object-fit-contain" src="/<?php echo $value['src']; ?>" autoplay loop muted></video>
		</div>
		<div class="card-text">
			<?php foreach ($fields as $attributes)
			{
				echo '<input ' . ArrayHelper::toString($attributes) . '>';
			} ?>
		</div>
		<div class="btn-group">
			<a class="btn btn-sm btn-success hasTooltip" radicalmart-field-gallery="add"
			   title="<?php echo Text::_('COM_RADICALMART_GALLERY_ACTION_ADD'); ?>">
				<span class="icon-plus"></span>
			</a>
			<a class="btn btn-sm btn-danger hasTooltip" radicalmart-field-gallery="remove"
			   title="<?php echo Text::_('COM_RADICALMART_GALLERY_ACTION_REMOVE'); ?>">
				<span class="icon-minus"></span>
			</a>
			<span class="btn btn-sm btn-primary hasTooltip" radicalmart-field-gallery="move"
				  title="<?php echo Text::_('COM_RADICALMART_GALLERY_ACTION_MOVE'); ?>">
				<span class="icon-arrows-alt"></span>
			</span>
		</div>
	</div>
</div>