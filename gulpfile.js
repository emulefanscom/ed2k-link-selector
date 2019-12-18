const { src, dest, parallel } = require('gulp');
const uglify = require('gulp-uglify');
const rename = require('gulp-rename');
const replace = require('gulp-replace');
const autoprefixer = require('autoprefixer');
const postcss = require('gulp-postcss');
var cssnano = require('cssnano');

function minicss() {
  return src('./ed2kls.dev.css')
  .pipe(postcss([
      autoprefixer(),
      cssnano()
    ]))
    .pipe(replace(/^ \*\//gm, ' */\n'))
    .pipe(rename("ed2kls.css"))
    .pipe(dest('./'));
}

function minijs() {
  return src('./ed2kls.dev.js')
    .pipe(uglify({
        output: {
          ie8: true,
          comments: '/(^!|@license|@preserve)/'
        }
    }))
    .pipe(rename("ed2kls.js"))
    .pipe(dest('./'));
}

function minijs_tinymce() {
  return src('./tinymce/editor_plugin.dev.js')
    .pipe(uglify({
        output: {
          ie8: true
        }
    }))
    .pipe(rename("editor_plugin.js"))
    .pipe(dest('./tinymce'));
}

exports.build = parallel(minicss, minijs, minijs_tinymce);