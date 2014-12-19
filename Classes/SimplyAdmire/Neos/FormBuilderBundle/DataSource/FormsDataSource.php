<?php
namespace SimplyAdmire\Neos\FormBuilderBundle\DataSource;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Neos\Service\DataSource\AbstractDataSource;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;

class FormsDataSource extends AbstractDataSource {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Form\Persistence\FormPersistenceManagerInterface
	 */
	protected $formPersistenceManager;

	/**
	 * @var string
	 */
	static protected $identifier = 'simplyadmire-neos-formbuilderbundle';

	/**
	 * Get data
	 *
	 * @param NodeInterface $node The node that is currently edited (optional)
	 * @param array $arguments Additional arguments (key / value)
	 * @return array JSON serializable data
	 */
	public function getData(NodeInterface $node = NULL, array $arguments) {
		$forms = $this->formPersistenceManager->listForms();
		$formIdentifiers = array();

		foreach ($forms as $key => $value) {
			$formIdentifiers[$key] = array('label' => $value['name']);
		}

		return $formIdentifiers;
	}

}