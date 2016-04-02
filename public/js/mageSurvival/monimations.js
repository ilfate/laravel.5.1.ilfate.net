/**
 * Created by Ilya Rubinchik (ilfate) on 06/11/15.
 */




MageS.Monimations = function (game) {
    this.game = game;

    this.extremeInElasticOutEasing = mojs.easing.path('M0,100 C50,100 50,100 50,50 C50,-15.815625 53.7148438,-19.1218754 60.4981394,0 C62.2625924,4.97393188 66.4286578,6.07928485 68.3303467,0 C71.3633751,-6.23011049 74.5489919,-1.10166123 75.7012545,0 C79.6946191,3.60945678 84.2063904,-0.104182975 84.2063905,0 C87.5409362,-2.25875668 90.4589294,-0.0327241098 93.4950242,0 C97.3271182,0.20445262 100,-0.104182352 100,0');
    //this.extremeInOutEasing = mojs.easing.path('M0,100 C50,100 50,100 50,50 C50,0 50,0 100,0');
    //this.fastSpin = mojs.easing.path('M 1.2711864,1.6949153 C 45.471519,8.8882369 41.125196,74.897877 49.035199,100.42373 57.256112,75.166388 55.374514,8.389801 100,0');
    this.parabola = mojs.easing.path('M -4.0677966e-8,0.42372886 C 50.556265,0.4136606 0.87095901,99.897877 49.035199,100.42373 99.628993,100.59012 50.713497,0.33895357 100,0');
    this.bounce50 = mojs.easing.path('M 0,50 C 5.3102312,37.282606 2.5221993,12.853007 11.47837,12.648512 21.89607,12.410646 14.728785,69.440119 25.730114,69.491526 32.1567,70.369873 28.768715,24.96061 37.76202,25.02908 c 6.616381,0.05037 4.34318,33.035725 11.476824,33.230797 2.842319,0.07772 9.484178,-20.527049 18.631075,-18.920776 C 77.550759,41.039138 84.713696,50.105127 100,50');
    this.scaleInIntencePath = mojs.easing.path('M 0,100 C 5.3102312,87.282606 2.5221993,12.853007 11.47837,12.648512 21.89607,12.410646 14.728785,69.440119 25.730114,69.491526 32.1567,70.369873 28.768715,24.96061 37.76202,25.02908 c 6.616381,0.05037 4.34318,33.035725 11.476824,33.230797 2.842319,0.07772 9.484178,-20.527049 18.631075,-18.920776 C 77.550759,41.039138 84.713696,50.105127 100,50');
    this.normalProgressionPath = mojs.easing.path('M 0,100 C 12.100531,70.665506 7.5311473,0.24009095 100,0');

    this.spinItem = function(el) {
        new mojs.Tween({
            repeat:   0,
            delay:    10,
            duration: 1000,
            onUpdate: function (progress) {
                var extremeInOutProgress = MageS.Game.monimations.parabola(progress) * 2 - 1;
                el[0].style.transform = 'scaleX(' + extremeInOutProgress + ')';
            }
        }).run();
    };

    this.bounce = function(el) {
        new mojs.Tween({
            repeat:   0,
            delay:    10,
            duration: 1000,
            onUpdate: function (progress) {
                var extremeInOutProgress = MageS.Game.monimations.bounce50(progress) * 2;
                el[0].style.transform = 'scale(' + extremeInOutProgress + ')';
            }
        }).run();
    };

    this.scaleIn = function(el) {
        el[0].style.transform = 'scale(0)';
        new mojs.Tween({
            repeat:   0,
            delay:    10,
            duration: 1500,
            onUpdate: function (progress) {
                var extremeInOutProgress = (MageS.Game.monimations.scaleInIntencePath(progress) * 3) - 0.5;
                el[0].style.transform = 'scale(' + (extremeInOutProgress) + ')';
            }
        }).run();
    }

    this.scaleInWithRotate = function(el) {
        el[0].style.transform = 'scale(0)';
        new mojs.Tween({
            repeat:   0,
            delay:    10,
            duration: 1500,
            onUpdate: function (progress) {
                var extremeInOutProgress = MageS.Game.monimations.scaleInIntencePath(progress) * 2;
                var normalProgression = MageS.Game.monimations.normalProgressionPath(progress);
                el[0].style.transform = 'scale(' + (extremeInOutProgress) + ') rotate(' +  (720 * normalProgression) + 'deg)';
            }
        }).run();
    }
};

