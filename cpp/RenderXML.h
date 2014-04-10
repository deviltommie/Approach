#include <iostream>
#include <vector>
#include <map>
#include <ostream>
#include <istream>
#include <fstream>
#include <sstream>


typedef unsigned long long int ProcUnit;
namespace Option{enum { id=0, tag, attributes, classes, properties, content, data, context, binding, component, service, message };}

namespace model
{
    struct XML
    {
        public:
        static ProcUnit ActiveRenderCount;
        ProcUnit RenderID;
        std::string tag,id,content;
        std::map<std::string,std::string> attributes;
        std::vector<std::string> classes;
        std::vector<XML*> children;

        //Set Unique Global Render ID based on static member ActiveRenderCount
        inline const void SetRenderID(){RenderID = ActiveRenderCount;  ++ActiveRenderCount;}

    /**********************/
    /*****CONSTRUCTORS*****/
    /***********************
    <Suggested Prototypes>

    XML(tag [,options])
    XML(tag,id [,options])
    XML(tag,id,classes [,options])
    XML(tag,id,classes,attributes [,options])
    XML(tag,id,classes,attributes [,options])

    NULL values to ignore them

    </Suggested Prototype>
    ***********************/

    /* Strict Typing */

        //std::string
        XML(std::string &_tag) throw() : tag (_tag){   XML::SetRenderID(); }
        XML(std::string &_tag, std::string &_id) throw() : tag (_tag), id (_id){   XML::SetRenderID(); }
        XML(std::string &_tag, std::string &_id, std::vector<std::string> &_classes) throw() : tag (_tag), id (_id), classes (_classes){    XML::SetRenderID(); }
        XML(std::string &_tag, std::string &_id, std::vector<std::string> &_classes, std::map<std::string,std::string> &_attributes) throw() : tag (_tag), id (_id), classes (_classes), attributes (_attributes){   XML::SetRenderID(); }

        //C style string (char*)
        XML(char* _tag) throw() : tag (_tag){   XML::SetRenderID(); }
        XML(char* _tag, char* _id) throw() : tag (_tag), id (_id){   XML::SetRenderID(); }
        XML(char* _tag, char* _id, std::vector<std::string> &_classes) throw() : tag (_tag), id (_id), classes (_classes){    XML::SetRenderID(); }
        XML(char* _tag, char* _id, std::vector<std::string> &_classes, std::map<std::string,std::string> &_attributes) throw() : tag (_tag), id (_id), classes (_classes), attributes (_attributes){   XML::SetRenderID(); }

    /*  Mixed Typing */

        //std::string
        XML(std::string &_tag, std::map<ProcUnit,void*> options) throw() : tag (_tag){   XML::SetRenderID(); SetOptions(options);  }
        XML(std::string &_tag, std::string &_id, std::map<ProcUnit,void*> options) throw() : tag (_tag), id (_id){   XML::SetRenderID(); SetOptions(options);  }
        XML(std::string &_tag, std::string &_id, std::vector<std::string> &_classes, std::map<ProcUnit,void*> options) throw() : tag (_tag), id (_id), classes (_classes){    XML::SetRenderID(); SetOptions(options);  }
        XML(std::string &_tag, std::string &_id, std::vector<std::string> &_classes, std::map<std::string,std::string> &_attributes, std::map<ProcUnit,void*> options) throw() : tag (_tag), id (_id), classes (_classes), attributes (_attributes){   XML::SetRenderID(); SetOptions(options);  }

        //C style string (char*)
        XML(char* _tag, std::map<ProcUnit,void*> options) throw() : tag (_tag){   XML::SetRenderID(); SetOptions(options);  }
        XML(char* _tag, char* _id, std::map<ProcUnit,void*> options) throw() : tag (_tag), id (_id){   XML::SetRenderID(); SetOptions(options);  }
        XML(char* _tag, char* _id, std::vector<std::string> &_classes, std::map<ProcUnit,void*> options) throw() : tag (_tag), id (_id), classes (_classes){    XML::SetRenderID(); SetOptions(options);  }
        XML(char* _tag, char* _id, std::vector<std::string> &_classes, std::map<std::string,std::string> &_attributes, std::map<ProcUnit,void*> options) throw() : tag (_tag), id (_id), classes (_classes), attributes (_attributes){   XML::SetRenderID(); SetOptions(options);  }

    /*  Options only    */

        XML(std::map<ProcUnit,void*> options){   XML::SetRenderID(); SetOptions(options);    }

    /**********************/
    /*****CLASS ACTIONS****/
    /**********************/

        void SetOptions(std::map<ProcUnit,void*> options)
        {
            std::map<ProcUnit,void*>::iterator option;
            for(option = options.begin(); option != options.end(); ++option)
            {
                //option.first is key, option.second is value
                ProcUnit key = option->first;

                switch(key)
                {
                    case Option::tag        : tag = *(std::string *)option->second; break;
                    case Option::id         : id = *(std::string *)option->second; break;
                    case Option::classes    : classes = *(std::vector<std::string> *)(option->second); break;
                    case Option::attributes : attributes = *(std::map<std::string,std::string> *)(option->second); break;
                    default : /* generic option call; */ break;
                }
            }
        }

    /**********************/
    /***STREAM TO CLASS****/
    /**********************/

        void operator<<(XML* object)
        {
            this->children.push_back(object);
        }
        void operator<<(XML object)
        {
            this->children.push_back(&object);
        }
    };
    ProcUnit XML::ActiveRenderCount=0;

    /**********************/
    /**XML STREAM OPERATOR*/
    /**********************/

    void operator<<( std::ostream& outputstream, const XML& object)
    {
        //Stream Opening Tag
        outputstream<<"<";
        if(!object.id.empty()) outputstream<<object.tag<<" id=\""<<object.id<<"\"";
        else outputstream<<object.tag;

        //Stream Rendered Classes
        if(!object.classes.empty())
        {
            outputstream<<" class=\"";
            for(ProcUnit i=0, L=object.classes.size(); i<L; ++i)
            {
                outputstream<<object.classes[i]<<" ";
            }
            outputstream<<"\"";
        }

        //Stream Rendered Attributes
        if(!object.attributes.empty())
        {
            for(std::map<std::string,std::string>::const_iterator attribute = object.attributes.begin(); attribute != object.attributes.end(); ++attribute)
            {
                outputstream<<" "<<attribute->first<<"=\""<<attribute->second<<"\"";
            }
        }
        outputstream<<">";

        if(!object.content.empty()) outputstream<<std::endl<<object.content<<std::endl;

        //Stream Children
        if(!object.children.empty())
        {
            for(ProcUnit i=0, L=object.children.size(); i<L; ++i)
            {
                outputstream<<std::endl<<*object.children[i];
            }
        }
        //Stream Closing Tag
        outputstream<<std::endl<<"</"<<object.tag<<">";
        //return outputstream;
    }

}
