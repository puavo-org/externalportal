import { recommended } from '@nextcloud/eslint-config'

export default [
	...recommended,
	{
		rules: {
			'jsdoc/require-jsdoc': 'off',
			'vue/first-attribute-linebreak': 'off',
			// Error logging in catch blocks is intentional; stray logs are not.
			'no-console': ['error', { allow: ['warn', 'error'] }],
		},
	},
]
