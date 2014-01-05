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

int main()
{
	string tag="li",id="test";
	map<string,string> attr;
	vector<string> classlist;

	classlist.push_back("big");
	classlist.push_back("red");
	classlist.push_back("button");

	attr["style"]="color: #eec; text-decoration: none;";
	attr["data-customattr"]="{'your':{'own':'system'},'of':0,'anything':{}}";

	//Create XML Nodes, Multiple Methods
	XML html("html"),head("head"),body("body"),screen("ul"), item(tag,id,classlist,attr);

	//Build a DOM, 2 different methods
	html.children.push_back(&head);
	html<<&body;
	body<<&screen;
	screen<<&item;

	item.content="this is a test of the thing";


	cout<<"<!DOCTYPE html>"<<endl<<html;

	return 0;
}