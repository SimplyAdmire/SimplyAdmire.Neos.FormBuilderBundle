(function(Editor) {
	Editor.FinisherEditor.FormBuilderBundleEmailFinisherEditor = Editor.FinisherEditor.EmailFinisherEditor.extend({
		templateName: 'Finisher-FormBuilderBundleEmailEditor'
	});

	Editor.FinisherEditor.FormBuilderBundleNodeRedirectFinisherEditor = Editor.ValidatorEditor.DefaultValidatorEditor.extend({
		templateName: 'Finisher-FormBuilderBundleNodeRedirectEditor'
	});
})(TYPO3.FormBuilder.View.ElementOptionsPanel.Editor);