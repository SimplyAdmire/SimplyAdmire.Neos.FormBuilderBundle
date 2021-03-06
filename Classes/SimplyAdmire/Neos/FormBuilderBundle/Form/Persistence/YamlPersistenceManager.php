<?php
namespace SimplyAdmire\Neos\FormBuilderBundle\Form\Persistence;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Utility\Files;

class YamlPersistenceManager extends \TYPO3\Form\Persistence\YamlPersistenceManager {

	/**
	 * @var \TYPO3\Flow\Package\PackageManagerInterface
	 * @Flow\Inject
	 */
	protected $packageManager;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Configuration\ConfigurationManager
	 */
	protected $configurationManager;

	/**
	 * @return void
	 */
	public function initializeObject() {
		$settings = $this->configurationManager->getConfiguration(\TYPO3\Flow\Configuration\ConfigurationManager::CONFIGURATION_TYPE_SETTINGS, 'TYPO3.Form');
		$this->injectSettings($settings);
	}

	/**
	 * @param array $settings
	 */
	public function injectSettings(array $settings) {
		if (isset($settings['yamlPersistenceManager']['savePath'])) {
			$this->savePath = $settings['yamlPersistenceManager']['savePath'];
			if (!is_dir($this->savePath) && !is_link($this->savePath)) {
				\TYPO3\Flow\Utility\Files::createDirectoryRecursively($this->savePath);
			}
		}
	}

	/**
	 * Returns the absolute path and filename of the form with the specified $persistenceIdentifier
	 * Note: This (intentionally) does not check whether the file actually exists
	 *
	 * @param string $persistenceIdentifier
	 * @return string the absolute path and filename of the form with the specified $persistenceIdentifier
	 */
	protected function getFormPathAndFilename($persistenceIdentifier) {
		$formFileName = sprintf('%s.yaml', $persistenceIdentifier);
		$globalPath = Files::concatenatePaths(array($this->savePath, $formFileName));
		if (file_exists($globalPath)) {
			return $globalPath;
		}

		/** @var \TYPO3\Flow\Package\Package $package */
		foreach ($this->packageManager->getActivePackages() as $package) {
			$packageFormPath = 'resource://' . $package->getPackageKey() . '/Private/Forms/' . $formFileName;
			if (file_exists($packageFormPath)) {
				return $packageFormPath;
			}
		}

		return $globalPath;
	}

	/**
	 * @return array
	 */
	public function listForms() {
		$forms = array();
		$originalSavePath = $this->savePath;

		/** @var \TYPO3\Flow\Package\Package $package */
		foreach ($this->packageManager->getActivePackages() as $package) {
			$this->savePath = 'resource://' . $package->getPackageKey() . '/Private/Forms';

			if (!is_dir($this->savePath)) {
				continue;
			}
			$packageForms = parent::listForms();
			foreach ($packageForms as $form) {
				$forms[$form['identifier']] = $form;
			}
		}

		$this->savePath = $originalSavePath;
		if (is_dir($this->savePath)) {
			$globalForms = parent::listForms();
			foreach ($globalForms as $form) {
				$forms[$form['identifier']] = $form;
			}
		}
		return $forms;
	}

	/**
	 * Save the array form representation identified by $persistenceIdentifier
	 *
	 * @param string $persistenceIdentifier
	 * @param array $formDefinition
	 */
	public function save($persistenceIdentifier, array $formDefinition) {
		$formPathAndFilename = \TYPO3\Flow\Utility\Files::concatenatePaths(array($this->savePath, sprintf('%s.yaml', $persistenceIdentifier)));
		file_put_contents($formPathAndFilename, \Symfony\Component\Yaml\Yaml::dump($formDefinition, 99));
	}

}