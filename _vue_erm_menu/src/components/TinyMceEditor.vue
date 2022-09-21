<template>
  <div class="border border-slate-200 relative">
    <textarea :id="id" rows="8" cols="40" name="description" class="relative" v-html="tinymce_content"></textarea>
  </div>
</template>

<script>
export default
{

  props: {
    content: ''
  },

  data(){
    return {
      id: 'tinymce-' + parseInt(10000 * Math.random()),
      tinymce: null,
      tinymce_content: '<h1>ALEJANDRO</h1>'
    }
  },

  created() {
    this.tinymce_content = this.content
  },

  mounted(){
    var self = this
    jQuery(document).ready(() => {
      self.loadEditor()
    })
  },

  methods: {
    loadEditor(){
      console.log('LOADING EDITOR')
      tinyMCE.init({
        //mode : "specific_textareas",
        //theme : "simple",
        /*plugins : "autolink, lists, spellchecker, style, layer, table, advhr, advimage, advlink, emotions, iespell, inlinepopups, insertdatetime, preview, media, searchreplace, print, contextmenu, paste, directionality, fullscreen, noneditable, visualchars, nonbreaking, xhtmlxtras, template",*/
        //editor_selector :"editor",
        mode : "exact",
        elements : this.id,
        menubar: false,
        //plugins: "lists, style",
        toolbar1: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | formatselect",
        //toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
        //toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor",
        //toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft",

        /*formats : {
          alignleft : {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'left'},
          aligncenter : {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'center'},
          alignright : {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'right'},
          alignfull : {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'full'},
          bold : {inline : 'span', 'classes' : 'bold'},
          italic : {inline : 'span', 'classes' : 'italic'},
          underline : {inline : 'span', 'classes' : 'underline', exact : true},
          strikethrough : {inline : 'del'},
          forecolor : {inline : 'span', classes : 'forecolor', styles : {color : '%value'}},
          hilitecolor : {inline : 'span', classes : 'hilitecolor', styles : {backgroundColor : '%value'}},
        }*/

      })
    },

    getContent() {
      return tinyMCE.get(this.id).getContent()
    }
  }
}
</script>
