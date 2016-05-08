/**
 * Tags Module Scripts for Phire CMS 2
 */

jax(document).ready(function(){
    if (jax('#tags-form')[0] != undefined) {
        jax('#checkall').click(function(){
            if (this.checked) {
                jax('#tags-form').checkAll(this.value);
            } else {
                jax('#tags-form').uncheckAll(this.value);
            }
        });
        jax('#tags-form').submit(function(){
            return jax('#tags-form').checkValidate('checkbox', true);
        });
    }
});