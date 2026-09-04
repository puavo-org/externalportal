<template>
	<div class="color-input-field">
		<label v-if="label" :for="inputId">{{ label }}</label>
		<input :id="inputId"
			ref="inputField"
			type="color"
			:aria-label="label ? undefined : t('externalportal', 'Color')"
			:value="modelValue"
			@input="$emit('update:modelValue', ($event.target as HTMLInputElement).value)">
	</div>
</template>

<script lang="ts">

import { t } from '@nextcloud/l10n'

export default {
	name: 'ColorInputField',
	props: {
		modelValue: {
			type: String,
			default: '',
		},

		label: {
			type: String,
			default: '',
		},
	},

	emits: [
		'update:modelValue',
	],

	data() {
		return {
			inputId: 'color-input-' + Math.random().toString(36).slice(2, 9),
		}
	},

	methods: {
		t,
		focus() {
			(this.$refs.inputField as HTMLInputElement).focus()
		},

		select() {
			(this.$refs.inputField as HTMLInputElement).select()
		},
	},
}
</script>

<style scoped>
.color-input-field {
	display: flex;
	align-items: center;
	gap: 8px;
}
</style>
