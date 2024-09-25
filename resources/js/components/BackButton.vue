<template>
    <div class="flex flex-auto mb-4">
        <button  @click="visit()" v-text="returnText"
              class="shadow  rounded  focus:outline-none
            ring-primary-200 dark:ring-gray-600 focus:ring bg-primary-500 hover:bg-primary-400 active:bg-primary-600 text-white
            dark:text-gray-800 inline-flex text-justify items-center font-bold px-4 h-9 text-sm">
        </button>
    </div>
</template>

<script setup lang="ts">
import {computed} from "vue";

const props = defineProps<{
  group: string
}>()

const returnText = computed<string>(() => 'Return to ' + props.group)

function visit() {
  return Nova.visit(`/nova-aws-cloudwatch/groups/`+props.group)
}
</script>
