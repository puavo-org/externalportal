<!--
SPDX-FileCopyrightText: Tobias Knöppler 6317548+thecalcaholic@users.noreply.github.com
SPDX-License-Identifier: AGPL-3.0-or-later
-->
<template>
	<div class="color-input-field">
		<label v-if="label" :for="inputId">{{ label }}</label>
		<input :id="inputId"
			ref="inputField"
			type="color"
			:value="value"
			@input="$emit('update:value', $event.target.value)">
	</div>
</template>

<script>
export default {
	name: 'ColorInputField',
	props: {
		value: {
			type: String,
			default: '',
		},
		label: {
			type: String,
			default: '',
		},
	},
	emits: [
		'update:value',
	],
	computed: {
		inputId() {
			return 'color-input-' + Math.random().toString(36).slice(2, 9)
		},
	},
	methods: {
		focus() {
			this.$refs.inputField.focus()
		},
		select() {
			this.$refs.inputField.select()
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
