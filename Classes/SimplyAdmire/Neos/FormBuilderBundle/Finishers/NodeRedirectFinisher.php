<?php
namespace SimplyAdmire\Neos\FormBuilderBundle\Finishers;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Form".            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Routing\UriBuilder;

/**
 * This finisher redirects to a specified nodePath.
 * @author support@simplyadmire.com
 */
class NodeRedirectFinisher extends \TYPO3\Form\Core\Model\AbstractFinisher {

	/**
	 * @var array
	 */
	protected $defaultOptions = array(
		'nodePath' => NULL,
		'delay' => 0,
		'statusCode' => 303,
	);

	/**
	 * @Flow\Inject
	 * @var \TYPO3\TYPO3CR\Domain\Service\ContextFactoryInterface
	 */
	protected $contextFactory;

	/**
	 * Executes this finisher
	 * @see AbstractFinisher::execute()
	 *
	 * @return void
	 * @throws \TYPO3\Form\Exception\FinisherException
	 */
	protected function executeInternal() {
		/** @var \TYPO3\Neos\Domain\Service\ContentContext $contentContext */
		$contentContext = $this->contextFactory->create(array());
		$node = $contentContext->getNode($this->parseOption('nodePath'));

		$uriBuilder = new UriBuilder();
		$uriBuilder->setRequest($this->finisherContext->getFormRuntime()->getRequest()->getMainRequest());
		$uri = $uriBuilder
			->reset()
			->setCreateAbsoluteUri(TRUE)
			->uriFor('show', array('node' => $node->getPath()), 'Frontend\Node', 'TYPO3.Neos');

		$delay = (integer)$this->parseOption('delay');
		$statusCode = $this->parseOption('statusCode');

		$escapedUri = htmlentities($uri, ENT_QUOTES, 'utf-8');

		$response = $this->finisherContext->getFormRuntime()->getResponse();
		$mainResponse = $response;
		$mainResponse->setContent('<html><head><meta http-equiv="refresh" content="' . $delay . ';url=' . $escapedUri . '"/></head></html>');
		$mainResponse->setStatus($statusCode);
		if ($delay === 0) {
			$mainResponse->setHeader('Location', (string)$uri);
		}

		$mainResponse->send();
 	}

}
