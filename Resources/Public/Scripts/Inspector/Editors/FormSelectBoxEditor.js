define(
	[
		'Library/jquery-with-dependencies',
		'emberjs'
	],
	function($, Ember) {
		var SelectboxOption = Ember.View.extend({
			tagName: 'option',
			attributeBindings: ['value', 'selected', 'disabled'],
			valueBinding: 'content.value',
			selectedBinding: 'content.selected',
			disabled: function() {
				if (this.get('content.disabled')) {
					return 'disabled';
				}
				return null;
			}.property('content.disabled'),
			template: Ember.Handlebars.compile('{{unbound view.content.label}}')
		});

		return Ember.CollectionView.extend({
			classNames: ['typo3-form-selectbox'],

			tagName: 'select',
			contentBinding: 'options',
			itemViewClass: SelectboxOption,

			value: '',
			allowEmpty: false,
			placeholder: '',

			attributeBindings: ['size', 'disabled'],

			values: [ ],

			init: function() {
				var that = this,
					endpointUrl = $('[rel="neos-site"]').attr('href').split('@')[0] + 'neos/management/formbuilder/list';

				this._super();

				$.ajax(endpointUrl, {
					dataType: 'json',
					success: function(data) {
						var values = [], option;

						$(data).each(function(value) {
							values.push({'value': data[value].identifier, 'label': data[value].identifier});
						});

						that.set('values', values);
						that.get('options');
					}
				});
			},

			options: function() {
				var options = [],
					values = this.get('values'),
					currentValue = this.get('value');

				if (this.get('allowEmpty')) {
					options.push(Ember.Object.create({value: '', label: this.get('placeholder')}));
				}

				$.each(values, function(value) {
					options.push(Ember.Object.create($.extend({
						selected: values[value].value === currentValue,
						value: values[value].value,
						disabled: false
					}, this)));
				});

				return options;
			}.property('values.@each', 'value', 'placeholder', 'allowEmpty'),

			onItemsChange: function() {
				var that = this;

				this.$().attr('data-placeholder', that.get('placeholder'));
				Ember.run.next(function() {
					that.$().trigger('chosen:updated');
				});
			}.observes('values.@each'),


			didInsertElement: function() {
				var that = this;

				if (this.get('placeholder')) {
					this.$().attr('data-placeholder', this.get('placeholder'));
				}

				// TODO Check value binding
				this.$().addClass('chosen-select').chosen({allow_single_deselect: true}).change(function() {
					that.set('value', that.$().val());
				});
			}
		});
	});