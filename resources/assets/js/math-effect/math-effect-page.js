/**
 * Created by Ilya Rubinchik (ilfate) on 12/09/14.
 */

function MathEffectPage() {

    this.hideMENameForm = function() {
        $('#MENameForm').hide();
        $('#MENameFormContainer').html('Name saved');
    }
}

MathEffectPage = new MathEffectPage();