<?php
namespace SimplyAdmire\Neos\FormBuilderBundle\Finishers;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\Arguments;
use TYPO3\Flow\Mvc\Routing\UriBuilder;

/**
 * This finisher redirects to a specified nodePath.
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
			->uriFor('show', array('node' => $node->getIdentifier()), 'Frontend\Node', 'TYPO3.Neos');

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
 	}

}
