<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Editors-xtd.article
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Editor Article buton
 *
 * @since  1.5
 */
class PlgButtonArticle extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * Display the button
	 *
	 * @param   string  $name  The name of the button to add
	 *
	 * @return  JObject  The button options as JObject
	 *
	 * @since   1.5
	 */
	public function onDisplay($name)
	{
		/*
		 * Javascript to insert the link
		 * View element calls jSelectArticle when an article is clicked
		 * jSelectArticle creates the link tag, sends it to the editor,
		 * and closes the select frame.
		 */
		$user  = JFactory::getUser();

		if ($user->authorise('core.create', 'com_content')
			|| $user->authorise('core.edit', 'com_content')
			|| $user->authorise('core.edit.own', 'com_content'))
		{
			$js = "
			function jSelectArticle(id, title, catid, object, link, lang)
			{
				var hreflang = '';
				if (lang !== '')
				{
					var hreflang = ' hreflang = \"' + lang + '\"';
				}
				var tag = '<a' + hreflang + ' href=\"' + link + '\">' + title + '</a>';
				jInsertEditorText(tag, '" . $name . "');
				jModalClose();
			}";

			JFactory::getDocument()->addScriptDeclaration($js);

			/*
			 * Use the built-in element view to select the article.
			 * Currently uses blank class.
			 */
			$link = 'index.php?option=com_content&amp;view=articles&amp;layout=modal&amp;tmpl=component&amp;' . JSession::getFormToken() . '=1';

			$button = new JObject;
			$button->modal = true;
			$button->class = 'btn btn-secondary';
			$button->link = $link;
			$button->text = JText::_('PLG_ARTICLE_BUTTON_ARTICLE');
			$button->name = 'file-add';
			$button->options = array(
				'height'     => '300px',
				'width'      => '800px',
				'bodyHeight' => '70',
				'modalWidth' => '80',
			);

			return $button;
		}
	}
}
