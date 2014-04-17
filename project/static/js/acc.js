/*************************************************************************

    APPROACH
    Organic, human driven software.
   
   
    COPYRIGHT NOTICE
    __________________
   
    Copyright 2002-2013, 2014 - Approach Foundation LLC, Garet Claborn
    All Rights Reserved.
 
    Title: ACC (Approach Command Client/Console), a system of accessing systems.

    Licensed under the Apache License, Version 2.0 (the "License");
    you may not use this file except in compliance with the License.
    You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

    Unless required by applicable law or agreed to in writing, software
    distributed under the License is distributed on an "AS IS" BASIS,
    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
    See the License for the specific language governing permissions and
    limitations under the License.

*/

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
function profile(target,RunOnce)
{
  attrs = $(target)[0].attributes;
  var IntentJSON = {};
  IntentJSON['support'] = {};
  IntentJSON.support.target={};
  for(var i=0;i<attrs.length;i++)
  {
    IntentJSON.support.target[attrs[i].nodeName]= attrs[i].value;
  }
  IntentJSON.support.target.tag = $(target).prop("tagName").toLowerCase();
  if(typeof(RunOnce)==='undefined')  IntentJSON.support.target.parent = profile(target.parentNode, true);
  return IntentJSON.support.target;
}

  function classSplit(incoming){return incoming.className.split(/\s+/);}
  function debug(reason, loggable){ console.log(reason);    console.log(loggable);  }
  function dragger(){   $(this).bind('mousedown',function(event){});    };

  var topChange=0, fullscreenModeActive = false, controlsHidden = false, html5=false,ApproachTotalRequestsAJAX=0
  hideControls= false,AnimatingControls = false, ob2 =null;
  ActiveTimeStream=0, ActiveFadePhase=0, FadeTimer=1, projectorClass='up';


  var Interface=function()
  {
    var $elf=this;
    this.Instance=0;
    $elf.Utility='http://service.nicegamez.com/Utility.php'

    this.InputArea= {};
    this.StagingArea= {};
    this.DisplayArea= {};    
    this.Buttons={ revert:[] };

    this.Collapse = true;
    this.active= true;

/*    
    //Interface DataSet
    this.HTML5 = 0;
    this.CueIndex=0;
    this.CueTimeline =0;
    this.id= 12345;
    this.title='Default Title';
    this.user='Anonymous Viewer';
    this.ip='0.0.0.0';
    this.fullscreen=-1;
    
    this.projectorDown=false;

    this.Sequencer=function(){}
    this.GetSortables=function(){}
*/
    this.call=
    {
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
                      /*  ACC Functions      */
                      case 'upload':        $elf.Buttons.upload.push(obj);          break;
                      case 'mediabrowse':   $elf.Buttons.mediabrowse.push(obj);     break;
                      case 'blocktext':     $elf.Buttons.blocktext.push(obj);       break;
    //                          case 'linetext':      $elf.Buttons.linetext.push(obj);        break;
                      
                      case 'dashboard':     $elf.Buttons.trackable.push(obj);       break;      //This means they can add the component, renderable or smart object to their personal dashboard. They can also organize their dashboard.   The dashboard is a FILE in a USER DIRECTORY o_O; other files should be there as we go. like message logs.
    //                          case 'preview':       $elf.Buttons.push(obj);                 break;
                      case 'save':          $elf.Buttons.push(obj);                 break;
                      case 'sort':          $elf.Buttons.sort.push(obj);            break;      //Multipurpose Sort for something like Hurry Curry sorter ........... >_>;    Arrange by 'property' or other things.. Function overloadable.
                      case 'revert':        $elf.Buttons.revert.push(obj);          break;      //Go back to database version of the edited stuff (Big Undo / Refetch the objects)
    
                      /*  Window Base Functions       */
                      case 'nestedcontrol': $elf.Buttons.nestedcontrols.push(obj);  break;
                      case 'dragger':       $elf.Buttons.dragger.push(obj);         break;
    
                      /*    Wizard Control Functions    */
                      case 'cancel':        $elf.Buttons.cancel.push(obj);          break;
                      case 'next':          $elf.Buttons.next.push(obj);            break;
                      case 'back':          $elf.Buttons.back.push(obj);            break;
                      case 'finish':        $elf.Buttons.finish.push(obj);          break;
    
                      default: break;
                    }
                });
                return $elf;
            });
        },
        Ajax:function(json,status,xhr)
        {
          if (typeof json != 'string')
          $.each(json, function(Activity, IntentJSON)
          {
            switch(Activity)
            {
                case 'APPEND': $elf.call.Append(IntentJSON); break;
                case 'REFRESH': $elf.call.Refresh(IntentJSON); break;
                case 'REMOVE': $elf.call.Remove(IntentJSON); break;
                case 'TRIGGER': $elf.call.Trigger(IntentJSON); break;
                  
                default: break;
            }
          });
          else{ console.log('Unhandled Response Code'); $elf.call.Append({'#terminal':json}); }          
        },
        Append: function(Info)
        {
          $.each(Info, function(Selector, Markup)
          {
            var DynamicElement=$(Markup).appendTo(Selector);
            $(Selector)[0].scrollTop = $(Selector)[0].scrollHeight;   //Scroll to bottom. Improve by, scroll to appended element
            
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
        Refresh: function(Info)
        {
          $.each(Info, function(Selector, Markup)
          {
            var DynamicElement=$(Selector).replaceWith(Markup);
//            $(Selector)[0].scrollTop = $(Selector)[0].scrollHeight;   //Scroll to bottom. Improve by, scroll to appended element
            
            //Bind Events for Dynamic Elements if they support Interface
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
        
        Service:function(target, IntentJSON)
        {
              var RequestType = '';
              var RequestNoun = '';
              var RequestVerb = '';
              
                for(var key in IntentJSON.command)
                {
                    RequestType = key;
                    for(var k in IntentJSON[key])
                    {
                        RequestNoun = k;
                        RequestVerb = IntentJSON[key][k];
                    }
                }
                console.log(IntentJSON.command);
                if (RequestNoun == 'Instance') alert('Instance');
                
                if(RequestNoun == 'Autoform')
                {
                    $($.ActiveInterface.Interface).find('.Content').find('form').each(function(i,obj)
                    {
                        // attach any data for ajax calls after verb
                        IntentJSON.command[RequestType][RequestNoun][RequestVerb][obj['action']]={};  //action is the web service
                        $(obj).find('input, textarea, select, checkbox,radio').each(function(i2,input)
                        {
                            //get all form values in wizard
                            IntentJSON.command[RequestType][RequestNoun][RequestVerb][obj['action']][$(input).attr('name')]=$(input).val();  
                        });              
                        // ability to bind submission with a type in the types collection (in the default implementation)
                        IntentJSON.command[RequestType][RequestNoun][RequestVerb][obj['action']]['type']=1; 
                    });
                }

    
              IntentJSON.support.target = profile(target);
              console.log('Support: ',IntentJSON.support.target);
              
              //Build default values in case not set
              /*
              IntentJSON['command'][EventClasses[RequestType]] = IntentJSON['command']
                  && IntentJSON['command'][EventClasses[RequestType]]
                  || {};
              IntentJSON['command'][EventClasses[RequestType]][EventClasses[RequestNoun]] = IntentJSON['command']
                  && IntentJSON['command'][EventClasses[RequestType]]
                  && IntentJSON['command'][EventClasses[RequestType]][EventClasses[RequestNoun]]
                  || {};
              IntentJSON['command'][EventClasses[RequestType]][EventClasses[RequestNoun]][EventClasses[RequestVerb]] = IntentJSON['command']
                  && IntentJSON['command'][EventClasses[RequestType]]
                  && IntentJSON['command'][EventClasses[RequestType]][EventClasses[RequestNoun]]
                  && IntentJSON['command'][EventClasses[RequestType]][EventClasses[RequestNoun]][EventClasses[RequestVerb]]
                  || {};*/
              console.log('Command: ',IntentJSON.command);
    
              var ReqData ={json: JSON.stringify(IntentJSON)};    //Switch to JSON3 ?
    
              ApproachTotalRequestsAJAX++;
          
              $.post( $elf.Utility,       //Any service which understands this protocol (ACCx)
                      ReqData,
                      $elf.call.Ajax,'text' );

        },
        menu:
        {
          drop:function()
          {
              $($elf.Active.Menu).slideToggle(400);
          }
        },
        fade:function()
        {
           FadeTimer +=250;
           if(FadeTimer%60001 == 0)
           {
               $elf.AnimatingControls = true;
               $($.ActiveInterface.Controls).fadeTo(1600,0, function(){$elf.AnimatingControls=false;$elf.controlsHidden=true;} );
           }
        },
        /*
        get:function(var what, var compliment)
        {
            switch(what)
            {
                case 'extern' : $elf.ActiveObject = 'waiting';
                                    $elf.call.Service('Connect', compliment);
                                    console.log('Connecting to service provider for complimentary object');
                                    setInterval($elf.call.Sync,750);    //The return value $elf.ActiveObject will be here - consider making it an array of objects (threadsafer direction)
                                    break;
            }
        },
        */
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
            $('.Interface')[0].style.height='26px';
            $('.Interface .InterfaceLayout .Header .AppMenu')[0].style.display='none';
            $('.Interface .InterfaceLayout .Content')[0].style.display='none';
            $('.Interface .InterfaceLayout .Footer')[0].style.display='none';
            $elf.Collapse = false;
    
          }
          else
          {
            $('.Interface')[0].style.height=$elf.RestoreHeight;
            $('.Interface .InterfaceLayout .Header .AppMenu')[0].style.display='inline-block';
            $('.Interface .InterfaceLayout .Content')[0].style.display='block';
            $('.Interface .InterfaceLayout .Footer')[0].style.display='block';
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
              //Really useful slider, this one function, "slidesLoadedHandler", can be bsd license (newest version as of 2012)
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
            url: 'http://approach.im/acc/console.php?publish='+this.get(0).COMPOSITION+'&instancename='+this.get(0).FeatureName+'&instancenum='+(this.get(0).FeatureIndex)+'&child='+this.get(0).ChildRef,
            data:
            {
              'json': '{ "request":{"PREVIEW":{"tokens":{'+ApproachUpdateTokens+'},"PageID":"'+this.get(0).FeaturePageID+'", "Child":"'+this.get(0).ChildPageID+'", "ChildRef":"'+this.get(0).ChildRef+'"}}}'
            },
            success:function(json, status, xhr){
              // successful request; do something with the data
              $('#ApproachControlUnit').empty();
              $.each(json.refresh, function(i,obj){
                  var ApproachUnit = $('#Dynamics #Notifier');
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
            url: 'http://approach.im/acc/console.php?publish='+this.get(0).publication+'&instancename='+this.get(0).FeatureName+'&instancenum='+(this.get(0).FeatureIndex)+'&child='+this.get(0).ChildRef,
            data:
            {
              'json': '{ "request":{"UPDATE":{"tokens":{'+ApproachUpdateTokens+'},"PageID":"'+this.get(0).FeaturePageID+'", "Child":"'+this.get(0).ChildPageID+'", "ChildRef":"'+this.get(0).ChildRef+'"}}}'
            },
            success:function(json, status, xhr){
              // successful request; do something with the data
              $('#ApproachControlUnit').empty();
              $.each(json.refresh, function(i,obj){
                  var ApproachUnit = $('#Dynamics #Notifier');
                  $('#'+i).html(obj);
              });
            },
            error:function(e,xhr,settings,exception){
              alert('error in:\\n'+settings.url+'error:\\n'+xhr.responseText);}
          });
        }
        /*
        drag: function(clickX)    //handling with jQueryUI now, maybe reimport from Approach Player
        {
    
        },*/
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

    return this;
  };


  function InterfaceEvents(e)
  {
    var classlist = e.target.className.split(/\s+/), c=0, L=0;

/*    if(classlist.length > 2)
    {
      RequestType = classlist[classlist.length-1] = classlist[classlist.length-1].replace(/\_/g,'.');
      RequestNoun = classlist[classlist.length-3] = classlist[classlist.length-3].replace(/\_/g,'.');
      console.log(classlist[classlist.length-2]);
      RequestVerb =  classlist[classlist.length-2] = classlist[classlist.length-2].replace(/\_/g,'.');
      console.log(RequestVerb);


      IntentJSON.support = {};
      IntentJSON.command = {};
      IntentJSON.command[RequestType]={};
      IntentJSON.command[RequestType][RequestNoun] ={};
      IntentJSON.command[RequestType][RequestNoun][RequestVerb]={};
*/
      // Form Harvesting Is Usually The Main Thing That Will Be Needed In Wizards
      // Should consider throwing wizard events, passing along e data & this
      // for the next/back/cancel/submit buttons
      
//    }

    if(e.type == 'click')
    {
		if( !$.contains($.ActiveInterface.Interface, e.target) )
		{
			$('.Interface').each(function(i,obj)
			{
				if($.contains($(obj)[0], e.target))
				{
				  $.ActiveInterface = obj.Interface;
				}
			});
    	}
        
            var RequestType = '';
    var RequestNoun = '';
    var RequestVerb = '';


    var IntentJSON = {};
    IntentJSON.support = {};
    IntentJSON.command = $(e.target).data('intent');
    
    
        if($.ActiveInterface.Active.MenuDown && classlist[0] != 'MenuButton' && classlist[0] != 'MenuSeparator')
        {
            $.ActiveInterface.call.menu.drop();
            $.ActiveInterface.Active.MenuDown = false;
        }
        switch(classlist[0])
        {
          case "closer" :       $.ActiveInterface.call.close(); break;
          case "collapse":      $.ActiveInterface.call.collapse(); break;

          case "MenuLabel":     if($.ActiveInterface.Active.MenuID == $(e.target).html())
                                {
                                    $.ActiveInterface.Active.MenuID = 'nullMenu';
                                    break;
                                }
                                $.ActiveInterface.Active.MenuID = $(e.target).html();
                                $.ActiveInterface.Active.Menu =  $(e.target).parent().find('.DropMenu');
                                $.ActiveInterface.call.menu.drop();
                                $.ActiveInterface.Active.MenuDown = true;
                                 break;
          case "Finish":        $.ActiveInterface.call.Service(e.target, IntentJSON); break;
          case "MenuButton":      $.ActiveInterface.call.Service(e.target, IntentJSON); break;
          case "Next":           var list=$.ActiveInterface.Interface.find('.Slide')[0].parentNode;
                                 list.requester="Next";
                                 $.ActiveInterface.call.slidesLoadedHandler(list);
                                 break;
          case "Back":           var list=$.ActiveInterface.Interface.find('.Slide')[0].parentNode;
                                 list.requester="Back";
                                 $.ActiveInterface.call.slidesLoadedHandler(list);
                                 break;
          default:				 break;
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

          $('.Interface .controls').bind('click mouseenter mouseleave', function(event){ InterfaceEvents(event); });
      }
  );

