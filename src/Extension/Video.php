<?php
/*
 * @package   plg_radicalmart_media_video
 * @version   __DEPLOY_VERSION__
 * @author    Dmitriy Vasyukov - https://fictionlabs.ru
 * @copyright Copyright (c) 2023 Fictionlabs. All rights reserved.
 * @license   GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 * @link      https://fictionlabs.ru/
 */

namespace Joomla\Plugin\RadicalMartMedia\Video\Extension;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\DispatcherInterface;
use Joomla\Event\Event;
use Joomla\Event\SubscriberInterface;

class Video extends CMSPlugin implements SubscriberInterface
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    bool
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $autoloadLanguage = true;

	/**
	 * Loads the application object.
	 *
	 * @var  \Joomla\CMS\Application\CMSApplication
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $app = null;

	/**
	 * Loads the database object.
	 *
	 * @var  \Joomla\Database\DatabaseDriver
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $db = null;

	/**
	 * Constructor
	 *
	 * @param   DispatcherInterface  &$subject  The object to observe
	 * @param   array                 $config   An optional associative array of configuration settings.
	 *                                          Recognized key values include 'name', 'group', 'params', 'language'
	 *                                          (this list is not meant to be comprehensive).
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function __construct(&$subject, $config = array())
	{
		parent::__construct($subject, $config);

		Factory::getApplication()->getLanguage()->load('plg_radicalmart_media_video',JPATH_ADMINISTRATOR);
	}

	/**
	 * Returns an array of events this subscriber will listen to.
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			'onRadicalMartGetGalleryFieldTypes'   => 'onRadicalMartGetGalleryFieldTypes',
			'onRadicalMartGetProductGalleryTypes' => 'onRadicalMartGetProductGalleryTypes'
		];
	}

	/**
	 * Method to change forms.
	 *
	 * @param   Event  $event  The event.
	 *
	 * @throws  \Exception
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function onRadicalMartGetGalleryFieldTypes(Event $event)
	{
		$context = $event->getArgument(0);
		$types   = $event->getArgument(1);
		$element = $event->getArgument(2);

		if (isset($types['video']))
		{
			return;
		}

		$video = [
			'type'                => 'video',
			'title'               => Text::_('PLG_RADICALMART_MEDIA_VIDEO_TYPE'),
			'icon'                => 'icon-video',
			'layout_item'         => 'plugins.radicalmart_media.video.field.gallery.item',
			'layout_template'     => 'plugins.radicalmart_media.video.field.gallery.template',
			'assets_extension'    => 'plg_radicalmart_media_video',
			'assets_script'       => 'plg_radicalmart_media_video.fields.gallery.video',
			'modal_url'           => 'index.php?option=com_media&view=media&tmpl=component',
			'modal_button_select' => 'window.RadicalMartFieldGalleryVideo.select(this)',
		];

		if (ComponentHelper::isEnabled('com_quantummanager'))
		{
			$video['title']               = Text::_('PLG_RADICALMART_MEDIA_VIDEO_TYPE');
			$video['icon']                = 'icon-video';
			$video['assets_script']       = 'plg_radicalmart_media_video.fields.gallery.video-quantum';
			$video['modal_url']           = 'index.php?option=com_quantummanager&tmpl=component&layout=window';
			$video['modal_button_select'] = 'window.RadicalMartFieldGalleryVideoQuantum.select(this)';
		}

		$types['video'] = $video;

		$event->setArgument(1, $types);
	}

	/**
	 * Method to change forms.
	 *
	 * @param   Event  $event  The event.
	 *
	 * @throws  \Exception
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function onRadicalMartGetProductGalleryTypes(Event $event)
	{
		$context  = $event->getArgument(0);
		$types    = $event->getArgument(1);
		$product  = $event->getArgument(2);
		$category = $event->getArgument(3);

		if (isset($types['video']))
		{
			return;
		}

		$types['video'] = [
			'layout_slide'   => 'plugins.radicalmart_media.video.gallery.slide',
			'layout_preview' => 'plugins.radicalmart_media.video.gallery.preview',
		];

		$event->setArgument(1, $types);
	}
}