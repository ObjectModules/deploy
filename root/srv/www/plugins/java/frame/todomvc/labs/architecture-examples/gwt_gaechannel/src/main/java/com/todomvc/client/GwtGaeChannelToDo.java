/*!! client */
package com.todomvc.client;

import java.util.*;

import com.google.gwt.core.client.*;
import com.google.gwt.user.client.*;
import com.google.gwt.user.client.ui.*;

import com.google.gwt.query.client.*;
import static com.google.gwt.query.client.GQuery.*;
import static com.google.gwt.query.client.css.CSS.*;

/**
 * Entry point class.
 * http://codebrief.com/2012/01/the-top-10-javascript-mvc-frameworks-reviewed/
 */
public class GwtGaeChannelToDo implements EntryPoint {

    private final ToDoGinjector injector = GWT.create(ToDoGinjector.class);

    public void onModuleLoad() {
		$("body").removeClass("coda-slider-no-js");
		ToDoView mainPanel = injector.getMainPanel();
        RootPanel.get("drop").add(mainPanel);		
		injectScript();
     }
	
	public static void injectScript() {
		String paths[] = new String []{
			"http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js",
			 "/scripts/jquery/handlebars-1.0.0.js",
			 "/scripts/jquery/ember.js",
			 "/scripts/jquery/ember-data.js"
		};		
		List<String> pathAsList = new ArrayList<String>(Arrays.asList(paths));
		injectScriptOneAfterAnother(pathAsList);
	}

	private static void injectScriptOneAfterAnother(final List<String> pathAsList) {
		ScriptInjector.fromUrl(pathAsList.remove(0)).setCallback(new Callback<Void, Exception>() {
		
			@Override
				public void onFailure(Exception reason) {
			}
			
			@Override
			public void onSuccess(Void result) {
				if (!pathAsList.isEmpty()) {
					injectScriptOneAfterAnother(pathAsList);
				}
				else{
					String target = "$.getScript('/scripts/init.js', function(){$.iframeHandler();}, true);";				
					target = "$(window).load(function(){setTimeout(function(){" + target + "}, 100);});";
					ScriptInjector.fromString(target).setRemoveTag(false).inject();
				}
			}
		}).inject();
	}
}
