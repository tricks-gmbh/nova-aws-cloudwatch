import Groups from './pages/Groups.vue'
import Streams from './pages/Streams.vue'
import StreamContent from './pages/StreamContent.vue'

Nova.booting((app: any, store: any) => {
  Nova.inertia('NovaAwsCloudwatchGroups', Groups)
  Nova.inertia('NovaAwsCloudwatchStreams', Streams)
  Nova.inertia('NovaAwsCloudwatchStreamContent', StreamContent)
})
