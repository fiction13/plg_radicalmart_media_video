/*
 * @package   plg_radicalmart_media_video
 * @version   __DEPLOY_VERSION__
 * @author    Dmitriy Vasyukov - https://fictionlabs.ru
 * @copyright Copyright (c) 2023 Fictionlabs. All rights reserved.
 * @license   GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 * @link      https://fictionlabs.ru/
 */

"use strict";

window.RadicalMartFieldGalleryVideoQuantum = {
	select(button) {
		let container = button.closest('[radicalmart-field-gallery="container"]');
		if (!container) {
			return;
		}

		let modal = Joomla.Modal.getCurrent();
		if (!modal) {
			return;
		}

		let iframe = modal.querySelector('iframe');
		if (!iframe) {
			return;
		}

		let iframeWindow = iframe.contentWindow;
		if (!iframeWindow) {
			return;
		}

		let QuantummanagerLists = iframeWindow.QuantummanagerLists,
			QuantumUtils = iframeWindow.QuantumUtils;

		if (!QuantummanagerLists || !QuantumUtils) {
			return;
		}

		let files = [];
		QuantummanagerLists.forEach((fm) => {
			let elements = fm.Quantumviewfiles.element.querySelectorAll('.field-list-files .file-item');
			elements.forEach((element) => {
				if (element.querySelector('input').checked) {
					files.push({
						'path': fm.data.path,
						'name': element.querySelector('.file-name').innerHTML,
						'scope': fm.data.scope,
						'preview': element.getAttribute('data-filep')
					});
				}
			});
		});

		if (files.length === 0) {
			return;
		}

		Promise.all(files.map((file) => {
				let url = QuantumUtils.getFullUrl('index.php?option=com_quantummanager&task=quantumviewfiles.getParsePath'
					+ '&path=' + encodeURIComponent(file.path + '/' + file.name)
					+ '&scope=' + file.scope);
				return new Promise((success, error) => {
					Joomla.request({
						url: url,
						data: {},
						method: 'POST',
						onSuccess: (response) => {
							return success({
								'file': file,
								'response': response
							})
						},
						onError: (e) => {
							return error(e);
						}
					});
				});
			})
		).then((results) => {
			results.forEach((result) => {
				try {
					let response = JSON.parse(result.response);
					if (response.path) {
						let src = response.path;
						let template = window.RadicalMartFieldGallery.getTypeTemplate(container, 'video');
						if (template) {
							template.querySelector('input[data-name*="[type]"]').value = 'video';
							template.querySelector('input[data-name*="[src]"]').value = src;

							let preview = template.querySelector('[radicalmart-field-gallery="preview"]');
							if (preview) {
								preview.setAttribute('src',
									Joomla.getOptions('system.paths').rootFull + src);
							}

							window.RadicalMartFieldGallery.appendItem(container, template);
						}
					}
				} catch (je) {
					Joomla.renderMessages({
						error: ['RadicalMartGallery: ' + je.message]
					});
					modal.close();
				}
			});

			modal.close();
		}).catch((e) => {
			Joomla.renderMessages({
				error: ['RadicalMartGallery: ' + e.message]
			});
			modal.close();
		});
	},
}
document.addEventListener('DOMContentLoaded', () => {
	document.querySelectorAll('[radicalmart-field-gallery="container"]').forEach((container) => {
		let modal = container.querySelector('[id*="_modal_type_video"]');
		if (modal) {
			modal.addEventListener('shown.bs.modal', (event) => {
				let iframe = event.target.querySelector('iframe');
				Joomla.Modal.setCurrent(event.target);
				if (iframe) {
					iframe.addEventListener('load', () => {
						let iframeWindow = iframe.contentWindow;
						if (iframeWindow) {
							iframeWindow.Joomla.Modal.setCurrent(event.target);
						}
					});
				}
			});
		}
	});
});
