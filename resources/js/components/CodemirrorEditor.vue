<template>
    <codemirror
      v-model="code"
      :style="{ height: 'calc(100vh - 19rem)' }"
      :autofocus="true"
      :indent-with-tab="true"
      :tab-size="2"
      :extensions="extensions"
      :disabled="true"
    />
  </template>

<script setup lang="ts">
    import { Codemirror } from 'vue-codemirror'
    import { oneDark } from '@codemirror/theme-one-dark'

    defineProps<{code:string}>()

    const extensions = [oneDark]

  </script>