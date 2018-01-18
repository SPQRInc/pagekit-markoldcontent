<template>
    <div class="uk-form uk-form-horizontal">
        <h1>{{ 'Markoldcontent Settings' | trans }}</h1>
        <div class="uk-form-row">
            <label class="uk-form-label">{{ 'Insert warning message automatically' | trans }}</label>
            <div class="uk-form-controls uk-form-controls-text">
                <input type="checkbox" v-model="package.config.autoinsert">
            </div>
        </div>
        <div class="uk-form-row" v-if="package.config.autoinsert">
            <label class="uk-form-label">{{ 'Position' | trans }}</label>
            <div class="uk-form-controls">
                <select class="uk-form-large" v-model="package.config.position">
                    <option value="top">{{ 'Top' | trans }}</option>
                    <option value="bottom">{{ 'Bottom' | trans }}</option>
                </select>
            </div>
        </div>
        <div class="uk-form-row">
            <label class="uk-form-label">{{ 'Message' | trans }}</label>
            <div class="uk-form-controls uk-form-controls-text">
                <v-editor type="code" :value.sync="package.config.message"></v-editor>
            </div>
        </div>
        <div class="uk-form-row">
            <label class="uk-form-label">{{ 'Period' | trans }}</label>
            <div class="uk-form-controls">
                <input class="uk-form-width-small uk-text-right" type="number"
                       v-model="package.config.period" min="0" number>
            </div>
        </div>
        <div class="uk-form-row uk-margin-top">
            <div class="uk-form-controls">
                <button class="uk-button uk-button-primary" @click="save">{{ 'Save' | trans }}</button>
            </div>
        </div>
    </div>
</template>

<script>

module.exports = {

	settings: true,

	props: ['package'],

	methods: {
		save: function save() {
			this.$http.post ('admin/system/settings/config', {
				name: 'spqr/markoldcontent',
				config: this.package.config
			}).then (function () {
				this.$notify ('Settings saved.', '');
			}).catch (function (response) {
				this.$notify (response.message, 'danger');
			}).finally (function () {
				this.$parent.close ();
			});
		}
	}
};

window.Extensions.components['markoldcontent-settings'] = module.exports;
</script>