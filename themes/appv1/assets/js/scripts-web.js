$(function () {

    console.log("scripts-web.js");
    
    /**
     *   Carregando Utilitários
     * */
    var $utility = new cemf.js.Utility();
    $utility.startWeb();

    if($utility.exists(".group-cards")){
        console.log("Carregando...");
        setTimeout(function(){
            requestModule();

            setInterval(function(){
                requestModule();
            },5000);

        },500);

        function requestModule() {
            console.log("push...");
            $url = "https://www.localhost/projetos/CEMF/protepop/app/push"
            $.post($url, {
                "grouping_id": 1
            }, function(response) {
                console.log(response);
                var token = response.data.token;

                $("#"+token+" #temperature").text(response.data.temperature);
                $("#"+token+"  #humidity").text(response.data.humidity);

                if(response.data.temperature > 30){
                    //VErmelho
                    $("#"+token+"  #temperature").css("color","#dc1515");
                    $("#"+token+"  #celsius").css("color","#dc1515");
                    $("#"+token+"-footer").css("background","#dc1515");
                }else if(response.data.temperature < 30 && response.data.temperature > 15){

                    //Verde
                    $("#"+token+"  #temperature").css("color","#7fc245");
                    $("#"+token+"  #celsius").css("color","#7fc245");
                    $("#"+token+"-footer").css("background","#7fc245");
                }else if(response.data.temperature < 15){

                    //Azul
                    $("#"+token+"  #temperature").css("color","#009bdb");
                    $("#"+token+"  #celsius").css("color","#009bdb");
                    $("#"+token+"-footer").css("background","#009bdb");
                }
    
            }, "json");
        }
    }

});