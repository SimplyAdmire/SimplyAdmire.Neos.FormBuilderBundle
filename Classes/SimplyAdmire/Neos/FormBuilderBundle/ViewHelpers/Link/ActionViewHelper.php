<?php
namespace SimplyAdmire\Neos\FormBuilderBundle\ViewHelpers\Link;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Form".            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Fluid\Core\ViewHelper;

/**
 * @author support@simplyadmire.com
 */
class ActionViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper {

	/**
	 * @var string
	 */
	protected $tagName = 'a';

	/**
	 * Initialize arguments
	 *
	 * @return void
	 * @api
	 */
	public function initializeArguments() {
		$this->registerUniversalTagAttributes();
		$this->registerTagAttribute('name', 'string', 'Specifies the name of an anchor');
		$this->registerTagAttribute('rel', 'string', 'Specifies the relationship between the current document and the linked document');
		$this->registerTagAttribute('rev', 'string', 'Specifies the relationship between the linked document and the current document');
		$this->registerTagAttribute('target', 'string', 'Specifies where to open the linked document');
	}

	/**
	 * Render the link.
	 *
	 * @param string $action Target action
	 * @param array $arguments Arguments
	 * @param string $controller Target controller. If NULL current controllerName is used
	 * @param string $package Target package. if NULL current package is used
	 * @param string $subpackage Target subpackage. if NULL current subpackage is used
	 * @param string $section The anchor to be added to the URI
	 * @param string $format The requested format, e.g. ".html"
	 * @param array $additionalParams additional query parameters that won't be prefixed like $arguments (overrule $arguments)
	 * @param boolean $addQueryString If set, the current query parameters will be kept in the URI
	 * @param array $argumentsToBeExcludedFromQueryString arguments to be removed from the URI. Only active if $addQueryString = TRUE
	 * @param boolean $absolute By default this ViewHelper renders links with absolute URIs. If this is FALSE, a relative URI is created instead
	 * @return string The rendered link
	 * @throws ViewHelper\Exception
	 */
	public function render($action, $arguments = array(), $controller = NULL, $package = NULL, $subpackage = NULL, $section = '', $format = '',  array $additionalParams = array(), $addQueryString = FALSE, array $argumentsToBeExcludedFromQueryString = array(), $absolute = TRUE) {
		$uriBuilder = $this->controllerContext->getUriBuilder();
		$request = $this->controllerContext->getRequest()->getHttpRequest()->createActionRequest();
		$uriBuilder->setRequest($request);

		$uriBuilder
			->reset()
			->setSection($section)
			->setCreateAbsoluteUri($absolute)
			->setArguments($additionalParams)
			->setAddQueryString($addQueryString)
			->setArgumentsToBeExcludedFromQueryString($argumentsToBeExcludedFromQueryString)
			->setFormat($format);
		try {
			$uri = $uriBuilder->uriFor($action, $arguments, $controller, $package, $subpackage);
		} catch (\Exception $exception) {
			\TYPO3\Flow\var_dump(array(
				$package,
				$controller,
				$action,
				$arguments
			));

			throw new ViewHelper\Exception($exception->getMessage(), $exception->getCode(), $exception);
		}

		$this->tag->addAttribute('href', $uri);
		$this->tag->setContent($this->renderChildren());
		$this->tag->forceClosingTag(TRUE);

		return $this->tag->render();
	}
}