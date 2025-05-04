<template>
  <div class="mindmap-container">
    <div ref="mindmap" class="mindmap-canvas"></div>
    <MapIntegration :markers="geoGoals"/>
  </div>
</template>

<script>
export default {
  props: ['goals'],
  data() {
    return {
      visNetwork: null,
      nodes: [],
      edges: []
    }
  },
  mounted() {
    this.initMindmap();
  },
  methods: {
    initMindmap() {
      // Configuration spécifique à Vue pour Vis.js
      this.nodes = this.goals.map(goal => ({
        id: goal.id,
        label: goal.title,
        color: this.getNodeColor(goal.type),
        x: goal.position.x,
        y: goal.position.y
      }));
      
      this.visNetwork = new vis.Network(
        this.$refs.mindmap,
        { nodes: this.nodes, edges: this.edges },
        { physics: true }
      );
    }
  }
}
</script>