<template>
	<NcInputField v-bind="{...$attrs, ...$props }"
				  ref="inputField"
				  type="color"
				  :show-trailing-button="false"
				  v-on="$listeners"
				  @input="handleInput">
		<!-- Default slot for the leading icon -->
		<slot />
	</NcInputField>
</template>

<script>
import NcInputField from "@nextcloud/vue/dist/Components/NcInputField"

export default {
	name: 'ColorInputField',
	components: {
		NcInputField
	},
	// Allow forwarding all attributes
	inheritAttrs: false,

	props: { ...NcInputField.props },
	emits: [
		'update:value',
	],
	methods: {
		/**
		 * Focus the input element
		 *
		 * @public
		 */
		focus() {
			this.$refs.inputField.focus()
		},

		/**
		 * Select all the text in the input
		 *
		 * @public
		 */
		select() {
			this.$refs.inputField.select()
		},

		handleInput(event) {
			/**
			 * Triggers when the value inside the color field is
			 * updated.
			 *
			 * @property {string} The new value
			 */
			this.$emit('update:value', event.target.value)
		}
	}

}
</script>
