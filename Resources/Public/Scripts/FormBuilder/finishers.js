(function(Editor) {
	Editor.FinisherEditor.FormBuilderBundleEmailFinisherEditor = Editor.FinisherEditor.EmailFinisherEditor.extend({
		templateName: 'Finisher-FormBuilderBundleEmailEditor'
	});

	Editor.FinisherEditor.FormBuilderBundleNodeRedirectFinisherEditor = Editor.ValidatorEditor.DefaultValidatorEditor.extend({
		templateName: 'Finisher-FormBuilderBundleNodeRedirectEditor'
	});

	Editor.FinisherEditor.FormBuilderBundleConfirmationFinisherEditor = Editor.FinisherEditor.EmailFinisherEditor.extend({
		templateName: 'Finisher-TYPO3FormConfirmationEditor',

		EditButton: Ember.Button.extend({
			tagName: 'a',
			click: function(event) {
				var that = this,
					$editor = $('#dialog-confirmation-editor p.hallo-editor');


				$editor.html(that.getPath('parentView.currentCollectionElement.options.message'));
				$editor.hallo({
					showAlways: true,
					plugins: {
						'halloformat': {},
						'halloreundo': {},
						'hallolink': {}
					}
				});

				$('#dialog-confirmation-editor').dialog({
					resizable: true,
					height: 500,
					width: 600,
					modal: true,
					buttons: {
						Ok: function() {
							that.setPath('parentView.currentCollectionElement.options.message', $('#dialog-confirmation-editor p.hallo-editor').html());
							TYPO3.FormBuilder.Model.Form.set('unsavedContent', true);
							$(this).dialog('close');
						}
					}
				});
				event.stopPropagation();
				return false;
			}
		})
	});

})(TYPO3.FormBuilder.View.ElementOptionsPanel.Editor);


$( "#dialog-confirm" )