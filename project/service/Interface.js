function slidesLoadedHandler(list)
{

     var activeElement = $(list).find('.active')[0];

      if(list.requester == 'Next' && $(list).children().last()[0] != $(activeElement)[0]) //&&   check extreme
       {
           $(activeElement).removeClass('active');
           activeElement = $(activeElement).next();
           $(activeElement)[0].className+=' active';

      }
      if(list.requester=='Back' && $(list).children().first()[0] != $(activeElement)[0]) //&& check extreme
      {
           $(activeElement).removeClass('active');
           activeElement = $(activeElement).prev();
           $(activeElement)[0].className+=' active';

      }
}
function profile(target)
{
  attrs = $(target)[0].attributes;
  var ObjectJSON = {};
  ObjectJSON['support'] = {};
  ObjectJSON.support.target={};
  for(var i=0;i<attrs.length;i++)
  {
    ObjectJSON.support.target[attrs[i].nodeName]= attrs[i].nodeValue;
  }
  ObjectJSON.support.target.tag = $(target).prop("tagName").toLowerCase();
  return ObjectJSON.support.target.innerHTML = $(target)[0].innerHTML;
}

  function classSplit(incoming){return incoming.className.split(/\s+/);}
  function debug(reason, loggable)
{
   console.log(reason);
   console.log(loggable);
}
  function dragger(){$(this).bind('mousedown',function(event){

  });};

  var topChange=0, fullscreenModeActive = false, controlsHidden = false, html5=false,ApproachTotalRequestsAJAX=0
  hideControls= false,AnimatingControls = false, ob2 =null;
  ActiveTimeStream=0, ActiveFadePhase=0, FadeTimer=1, projectorClass='up';


  var Interface=function()
     {
          var $elf=this;
          this.Sequencer=function()
          {

          }
          this.GetSortables=function()
          {

          }
          this.call={
              init:function(Interface)
              {
                  $elf.Interface = Interface;
                  $(Interface).draggable();
                  $elf.Controls = $(Interface).find('.controls')[0];

                  $($elf.Controls).find('li').each(function(i,obj)  //binding control buttons to .control li's
                  {
                      var classes = classSplit(obj);
                      $.each(classes, function(i,_class)
                      {
                          switch(_class)
                          {
                              case 'upload': $elf.Buttons.upload.push(obj); break;
                              case 'mediabrowse': $elf.Buttons.mediabrowse.push(obj); break;
                              case 'blocktext': $elf.Buttons.blocktext.push(obj); break;
                              case 'linetext': $elf.Buttons.linetext.push(obj); break;
                              case 'preview': $elf.Buttons.push(obj); break;
                              case 'save': $elf.Buttons.push(obj); break;
                              case 'nestedcontrol': $elf.Buttons.nestedcontrols.push(obj); break;
                              case 'social': $elf.Buttons.social.push(obj); break;
                              case 'dragger': $elf.Buttons.dragger.push(obj); break;          //
                              case 'dashboard': $elf.Buttons.trackable.push(obj); break;      //This means they can add the component, renderable or smart object to their personal dashboard. They can also organize their dashboard.   The dashboard is a FILE in a USER DIRECTORY o_O; other files should be there as we go. like message logs.
                              case 'sort': $elf.Buttons.sort.push(obj); break;                      //Multipurpose Sort for something like Hurry Curry sorter ........... >_>;    Arrange by 'property' or other things.. Function overloadable.
                              case 'revert': $elf.Buttons.revert.push(obj); break;                //Go back to database version of the edited stuff (Big Undo / Refetch the objects)

                              case 'cancel': $elf.Buttons.cancel.push(obj); break;
                              case 'next': $elf.Buttons.next.push(obj); break;
                              case 'back': $elf.Buttons.back.push(obj); break;
                              case 'finish': $elf.Buttons.finish.push(obj); break;

                              default: break;
                          }
                      });
                      return $elf;
                  });
              },

              /*
                Add new commands coming from server here.
              */
              Ajax:function(json,status,xhr)
              {
                $.each(json, function(Activity, ObjectJSON)
                {
                  switch(Activity)
                  {
                      case 'APPEND': $elf.call.Append(ObjectJSON); break;
                      default: break;
                  }
                });
              },
              Append: function(Info)
              {
                $.each(Info, function(Selector, Markup)
                {
                  var DynamicElement=$(Markup).appendTo(Selector);
                  var classes = classSplit(DynamicElement[0]);
                  $.each(classes, function(i,_class)
                  {
                    if(_class == 'Interface')
                    {
                        DynamicElement.Interface = new Interface();
                        DynamicElement.Interface.call.init(DynamicElement);

                        $.ActiveInterface=DynamicElement.Interface;
                        DynamicElement.Interface.active = true;
                        DynamicElement.find('.controls').bind('click mouseenter mouseleave', function(event){ InterfaceEvents(event); });

                    }
                  });
                });
              },
              menu:function()
              {
                this.drop=function()
                {
                    $($elf.Active.Menu).slideToggle(400);
                };
              },
              drag:function(){},
              fade:function()
              {
                 FadeTimer +=250;
                 if(FadeTimer%60001 == 0)
                 {
                     $elf.AnimatingControls = true;
                     $($.ActiveInterface.Controls).fadeTo(1600,0, function(){$elf.AnimatingControls=false;$elf.controlsHidden=true;} );
                 }
              },

              get:function(what,compliment)
              {
              /*    switch(what)
                  {
                      case 'extern' : $elf.ActiveObject = 'waiting';
                                          $elf.call.Service('Connect', compliment);
                                          console.log('Connecting to service provider for complimentary object');
                                          setInterval($elf.call.Sync,750);    //The return value $elf.ActiveObject will be here - consider making it an array of objects (threadsafer direction)
                                          break;
                  }
              */},

              sort: function()
              {
                      this.Sortables = GetSortables($elf.Current.InputRegion);
                      this.Sorted = {};

                      for(Sortable in this.Sortables){    $elf.Sequencer(Sortable);  }

                      while($elf.Sorting)
                      {
                          this.Sorted[$elf.Sequencer.ActiveSort]=$elf.Sequencer();
                      }
              },
              collapse: function()
              {
                if($elf.Collapse==true)
                {
                  $elf.RestoreHeight = $('.Interface')[0].style.height;
                  $('.Interface')[0].style.height='22px';
                  $('.Interface .CaseLayout .Header .AppMenu')[0].style.display='none';
                  $('.Interface .CaseLayout .Content')[0].style.display='none';
                  $('.Interface .CaseLayout .Footer')[0].style.display='none';
                  $elf.Collapse = false;

                }
                else
                {
                  $('.Interface')[0].style.height=$elf.RestoreHeight;
                  $('.Interface .CaseLayout .Header .AppMenu')[0].style.display='inline-block';
                  $('.Interface .CaseLayout .Content')[0].style.display='block';
                  $('.Interface .CaseLayout .Footer')[0].style.display='block';
                  $elf.Collapse = true;
                }

              },
              close: function()
              {
                $($elf.Interface).remove();
              },
              save: function()
              {

              },
              slidesLoadedHandler: function(list)
                {

                 var activeElement = $(list).find('.active')[0];

                  if(list.requester == 'Next' && $(list).children().last()[0] != $(activeElement)[0]) //&&   check extreme
                   {
                       $(activeElement).removeClass('active');
                       activeElement = $(activeElement).next();
                       $(activeElement)[0].className+=' active';

                  }
                  if(list.requester=='Back' && $(list).children().first()[0] != $(activeElement)[0]) //&& check extreme
                  {
                       $(activeElement).removeClass('active');
                       activeElement = $(activeElement).prev();
                       $(activeElement)[0].className+=' active';

                  }
              },
              preview: function()
              {
                  ApproachTotalRequestsAJAX++;
                  $.ajax({
                  type: 'POST',
                  dataType: 'json',
                  url: 'http://debug.mydamnchannel.com/Approach/Service.php?publish='+this.get(0).PUBLICATION+'&instancename='+this.get(0).FeatureName+'&instancenum='+(this.get(0).FeatureIndex)+'&child='+this.get(0).ChildRef,
                  data:
                  {
                    'json': '{ "request":{"PREVIEW":{"tokens":{'+ApproachUpdateTokens+'},"PageID":"'+this.get(0).FeaturePageID+'", "Child":"'+this.get(0).ChildPageID+'", "ChildRef":"'+this.get(0).ChildRef+'"}}}'
                  },
                  success:function(json, status, xhr){
                    // successful request; do something with the data
                    $('#ApproachControlUnit').empty();
                    $.each(json.refresh, function(i,obj){
                        var ApproachUnit = $('#ApproachHeroUnit');
                        $('#'+i).html(obj);

                    });
                  },
                  error:function(e,xhr,settings,exception){
                    alert('error in:\\n'+settings.url+'error:\\n'+xhr.responseText);}
                });
              },
              next: function() {alert('checking next button ');},
              revert: function()
              {
                 ApproachTotalRequestsAJAX++;
                 $.ajax({
                  type: 'POST',
                  dataType: 'json',
                  url: 'http://debug.mydamnchannel.com/Approach/Service.php?publish='+this.get(0).publication+'&instancename='+this.get(0).FeatureName+'&instancenum='+(this.get(0).FeatureIndex)+'&child='+this.get(0).ChildRef,
                  data:
                  {
                    'json': '{ "request":{"UPDATE":{"tokens":{'+ApproachUpdateTokens+'},"PageID":"'+this.get(0).FeaturePageID+'", "Child":"'+this.get(0).ChildPageID+'", "ChildRef":"'+this.get(0).ChildRef+'"}}}'
                  },
                  success:function(json, status, xhr){
                    // successful request; do something with the data
                    $('#ApproachControlUnit').empty();
                    $.each(json.refresh, function(i,obj){
                        var ApproachUnit = $('#ApproachHeroUnit');
                        $('#'+i).html(obj);

                    });
                  },
                  error:function(e,xhr,settings,exception){
                    alert('error in:\\n'+settings.url+'error:\\n'+xhr.responseText);}
                });
              },
              social: function()
              {

              },

              drag: function(clickX)
              {
                  $elf.Buttons.volumeDragger.style.width=clickX-$($elf.Buttons.volume).offset().left + 'px';
                  volumecalc=$($elf.Buttons.volumeDragger).width()/$($elf.Buttons.volume).width();

                  $elf.Video.volume = 0.125*volumecalc.toFixed(6);
                  $elf.volume = $elf.Video.volume;
                 },
              cue:function()
              {
                   $($elf.Controls).trigger('mouseenter');
                   setTimeout(function()
                   {
                      $elf.call.pause();
                      $.each($elf.CueList[$elf.CueIndex].Placement, function(i, obj)
                      {
                          switch(obj)
                          {
                            case 'projector': $($elf.Interface).find( '.topBar').trigger('click'); $elf.call.projector(); break;
                            case 'overlay'  : $elf.call.overlay();break;
                            case 'companion': $elf.call.companion();break;
                            default: break;
                          }

                      });
                   },800);

              },

              projector:function()
              {
                  var requested = "Smart37";
                  ApproachTotalRequestsAJAX++;
                  $.ajax(
                  {
                            type: 'POST',
                            dataType: 'json',
                            url: 'http://debug.mydamnchannel.com/Approach/Service.php?publish=hexacode&instancename=Post&instancenum=0',
                            data:
                            {
                              'json': '{"request":{"REQUEST":{"tokens":{},"PageID":"Smart18", "Child":"Smart45", "ChildRef":"0"}}}'
                            },
                            success:function(json, status, xhr)
                            {
                                  // successful request; do something with the data
                                  $($elf.Buttons.projector).find('.projector_content').empty();
                                  $.each(json.refresh, function(i,obj)
                                  {
                                      $($elf.Buttons.projector).find('.projector_content').html(obj);
                                  });

                            },
                           error:function(e,xhr,settings,exception)
                           {
                              $($elf.Buttons.projector).find('.projector_content').html('ERROR');
                           }
                   });
              },
              overlay:function()
              {
                 $($elf.Interface).append('<div class="videoOverlay" style="background-color:#000; position:absolute; top:0px; left:0px; z-index:999; display:block; width:100%; height:100%;"></div>');
              },
              companion:function()
              {

              },
              Navigate:function(target, EventClasses)
              {
                if($(target).find('ul').length) //Subtrees exist
                {
                }
                else    //No subtrees, fetch appropriate content
                {
                }
              },
              Service:function(target, EventClasses)
              {
                    var RequestType = EventClasses.length-1;
                    var RequestDomain = EventClasses.length-3;
                    var RequestKey = EventClasses.length-2;

                    ObjectJSON.support = {};
                    ObjectJSON.command = {};
                    ObjectJSON.command[RequestType]={};
                    ObjectJSON.command[RequestType][RequestDomain] ={};
                    ObjectJSON.command[RequestType][RequestDomain][RequestKey]={};

                    //Form Harvesting Is Usually The Main Thing That Will Be Needed In Anyone's Wizards
                    if(RequestDomain == 'Wizard')
                    {
                        $($.ActiveInterface.Interface).find('.Content').find('form').each(function(i,obj)
                        {
                            ObjectJSON.command[RequestType][RequestDomain][RequestKey][obj['action']]={};
                            $(obj).find('input, textarea, select, checkbox,radio').each(function(i2,input)
                            {
                                ObjectJSON.command[RequestType][RequestDomain][RequestKey][obj['action']][$(input).attr('name')]=$(input).val();
                            });
                            ObjectJSON.command[RequestType][RequestDomain][RequestKey][obj['action']]['category']=6;
                        });
                        ObjectJSON['command'][EventClasses[RequestType]]={};
                        ObjectJSON['command'][EventClasses[RequestType]][EventClasses[RequestDomain]] = EventClasses[RequestKey];
                    }

                    ObjectJSON.support.target = profile(target);

                    var ReqData ={json: JSON.stringify(ObjectJSON)};

                    ApproachTotalRequestsAJAX++;
                    $.post( 'http://debug.mydamnchannel.com/Approach/Services/Utility.php',
                            ReqData,
                            function(json,status,xhr){$elf.call.Ajax(json,status,xhr); $('#Dynamics').html(json['#Dynamics']); });
              }
          };
  //end $elf.call


     this.Focus= {};
     this.Controls= {};

     this.Active =
     {
        Menu:{},
        MenuID: 'nullMenu',
        MenuDown:false,
        Field:{},
        User:{},
        Task:{},
        SyncList:{}
     };

     this.InputArea= {};
     this.StagingArea= {};
     this.DisplayArea= {};

     this.Buttons={ revert:[] };


     this.Instance= 0;

  //Interface DataSet
     this.HTML5 = 0;
     this.CueIndex=0;
     this.CueTimeline =0;
     this.Collapse = true;

     this.active= false;
     this.id= 12345;
     this.title='Default Title';
     this.user='Anonymous MyDamnChannel Viewer';
     this.ip='0.0.0.0';
     this.fullscreen=-1;




      this.projectorDown=false;

     return this;
  };

  function InterfaceEvents(e)
  {
    var classlist = e.target.className.split(/\s+/), c=0, L=0;
    var RequestType = 0;
    var RequestDomain = 0;
    var RequestKey = 0;
    var ObjectJSON = {};

    if(e.type == 'click')
    {
        if($.ActiveInterface.Active.MenuDown && classlist[0] != 'MenuItem' && classlist[0] != 'MenuSeparator')
        {
            $.ActiveInterface.call.menu.drop();
            $.ActiveInterface.Active.MenuDown = false;
        }
        switch(classlist[0])
        {
          case "close" :        $.ActiveInterface.call.close(); break;
          case "collapse":      $.ActiveInterface.call.collapse(); break;

          case "MenuLabel":     if($.ActiveInterface.Active.MenuID == $(e.target).html())
                                {
                                    $.ActiveInterface.Active.MenuID = 'nullMenu';
                                    break;
                                }
                                $.ActiveInterface.Active.MenuID = $(e.target).html();
                                $.ActiveInterface.Active.Menu =  $(e.target).parent().find('.DropMenu');
                                $.ActiveInterface.call.menu.drop();
                                $.ActiveInterface.Active.MenuDrown = true;
                                 break;
          case "Service":       $.ActiveInterface.call.Service(e.target, classlist); break;
          case "Navigate":      $.ActiveInterface.call.Navigate(e.target, classlist); break;
          case "Finish":        $.ActiveInterface.call.Service(e.target, classlist); break;
          case "MenuItem":      $.ActiveInterface.call.Service(e.target, classlist); break;
          case "Next":           var list=$.ActiveInterface.Interface.find('.Slide')[0].parentNode;
                                 list.requester="Next";
                                 $.ActiveInterface.call.slidesLoadedHandler(list);
                                 break;
          case "Back":           var list=$.ActiveInterface.Interface.find('.Slide')[0].parentNode;
                                 list.requester="Back";
                                 $.ActiveInterface.call.slidesLoadedHandler(list);
                                 break;
          default:                 break;
        }
    }
    else if (e.type =='CueEvent')
    {
        var classlist = e.target.className.split(/\s+/), c=0, L=0;
        $.ActiveInterface.call.cue();
    }
  }

  $(document).ready(
      function()
      {
          $('.Interface').each(function(instance, InterfaceMarkup)
          {
              InterfaceMarkup.Interface=new Interface();
              InterfaceMarkup.Interface.call.init(InterfaceMarkup);
              $.ActiveInterface=InterfaceMarkup.Interface;
          });

          $('.controls').bind('click mouseenter mouseleave', function(event){ InterfaceEvents(event); });
      }
  );

