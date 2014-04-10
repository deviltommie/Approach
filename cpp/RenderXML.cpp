#include "RenderXML.h"

using model::XML;
using namespace std;
/*
//Comment | Uncomment for collision control with std

using std::cout;
using std::endl;
using std::map;
using std::vector;
using std::string;
*/

string to_string(unsigned int number);

int main()
{
//    std::ofstream outfile ("new.xml",std::ofstream::out);

	string tag="li",id="NavigationArea";
	map<string,string> attr;
	vector<string> classlist;

	classlist.push_back("big");
	classlist.push_back("red");
	classlist.push_back("button");

	attr["style"]="color: #eec; text-decoration: none;";
	attr["data-customattr"]="{'your':{'own':'system'},'of':0,'anything':{}}";

	//Create XML Nodes, Multiple Methods
	XML html("html"),head("head"),body("body"),Stage("ul","Stage"),
        NavigationArea(tag,id,classlist,attr),Screen("li","Screen"),FooterArea("li","FooterArea"),Content("ul","ContentGroup");


	//Build a DOM, 2 different methods
	html.children.push_back(&head);
	html<<&body;
	body<<&Stage;
	Stage<<&NavigationArea;
	Stage<<&Screen;
	Stage<<&FooterArea;

	Screen<<&Content;

	for(unsigned int i=0; i<32; i++)
    {
        classlist.clear();
        attr.clear();

        unsigned int n=i;

        tag="li";
        id="mrID"+to_string(n);
        attr["data-test"]="Noun.Verb.ACTION";//+to_string(n);
        attr["style"]="color: blue; font-size: 12px;";//+to_string(n)+"px;";
        attr["onclick"]="EventRouter(this,1)";//+to_string(n)+");";
        classlist.push_back(to_string(n));
        classlist.push_back("ListItem");
        classlist.push_back("class");

        XML* DynamicXML=new XML(tag,id,classlist,attr);
        DynamicXML->content="this is sub item "+to_string(n)+" in the stack";

        Content<<DynamicXML;
    }

	Content.content="this is a test of the thing";


	cout<<"<!DOCTYPE html>"<<endl<<html;

//	cout.close();

	return 0;
}

string to_string(unsigned int number)
{
   stringstream ss;//create a stringstream
   ss << number;//add number to the stream
   return ss.str();//return a string with the contents of the stream
}
