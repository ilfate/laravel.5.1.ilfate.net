/**
 * Created by vladimir on 17.06.17.
 */

function Human(newName) {
    var name = newName;
    var children = [];
     var spouse = false;

    this.setName = function(newName) {
        name = newName;
    };
    this.getName = function() {
        return name;
    };
    this.addChild = function(tghnghn) {
        children.push(tghnghn);
    };
    this.getChildren = function () {
        return children;
    };
    this.getFirstChild = function () {
        return children[0];
    };
    this.addSpouse = function(newSpouse) {
        spouse = newSpouse;
    };
    this.getSpouse = function () {
        return spouse;
    };
    this.render = function(where) {
        var html = '<div class="human-container"><div class="human">' + name + '</div><div class="spouse"></div><div class="clear"></div><div class="children"></div></div>';
        var el = $(html);
        where.append(el);

        var childrenEl = el.find('.children');
        for (var key in children) {
            children[key].render(childrenEl);
        }
        if (spouse) {
            var spouseEl = el.find(' > .spouse');
            el.find('> .human').after('<span class="line">-</span>');
            spouse.render(spouseEl);
        }
    }
}
var lida = new Human('Lida');
var vladimir = new Human('Vladimir');
var marina = new Human('marina');
var nastya = new Human('Nastya');
var saschka = new Human('Saschka');
var ilya = new Human('Ilya');
var nadya = new Human('Nadya');
var veronika = new Human('Veronika');

lida.addChild(marina);
vladimir.addChild(nastya);
vladimir.addChild(saschka);
marina.addSpouse(vladimir);
nastya.addSpouse(ilya);
saschka.addSpouse(nadya);
saschka.addChild(veronika);


console.log(
    lida
    .getFirstChild()
    .getSpouse()
    .getFirstChild()
    .getSpouse()
    .getName()
);

 $(document).ready(function(){

    lida.render($('#start'));
 });





