<template>
    <div class="aws-cloudwatch-scoped">
        <back-button :group="group"></back-button>
        <CodemirrorEditor :code="streamContent"></CodemirrorEditor>
    </div>
</template>

<script setup lang="ts">
import BackButton from '../components/BackButton.vue';
import CodemirrorEditor from "../components/CodemirrorEditor.vue";

defineProps<{
  group: string,
  stream: string,
  streamContent: string,
}>()

</script>


<style lang="scss">
  @import '../../css/tool.css';
</style>