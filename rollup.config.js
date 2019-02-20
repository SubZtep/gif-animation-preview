import typescript from "rollup-plugin-typescript"

export default {
  input: "./gapplayer/gapplayer.ts",
  plugins: [
    typescript()
  ],
  output: {
    file: "./dist/gapplayer.js",
    format: "iife"
  }
}
