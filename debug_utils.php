<?php

// to easier find the log entries in the console, we will prefix them with [CUSTOM_DEBUG_LOG]
//in phpstorm a grep console plugin can be used to filter out the debug logs in real time
// since phpstorm 2024.3 xdebug_notify is supported in debug console https://www.jetbrains.com/phpstorm/whatsnew/#version-2024-3-debugging
// setup:
// 1. Install the grep console and live plugins in phpstorm, then restart the IDE.
// https://plugins.jetbrains.com/plugin/7125-grep-console
// https://plugins.jetbrains.com/plugin/7282-liveplugin
// 2. Navigate to services tab -> select docker-compose container for this project -> php -> log tab
// 3. Right-click on the text and select "Open Grep Console settings"
// 4. In the filtering pane select Add new item
// 5. In the expression field enter: .*
// 6. Click "Extension..." button and select "Create 'LivePlugin' example"
// 7. Paste and save the code bellow
// 8. Run the plugin from  in Live plugins tab - you should see a "'CUSTOM_DEBUG_LOG' registered" monit
// 9. Change Action for the added item to "CUSTOM_DEBUG_LOG"
// 10. Tick the checkbox to the left of the added item and in the bottom right select "Apply" and "OK"
// 11. Now you should see only the debug logs in the console

// live extension filter code for the grep console plugin bellow (works in phpstorm services tab - docker container log)
// doesn't work in the separate tab created by the plugin, when the original log is kept intact - IDK why
/*
package example

import com.intellij.ide.plugins.IdeaPluginDescriptorImpl
import com.intellij.ide.plugins.PluginManager
import com.intellij.openapi.extensions.PluginId

import java.util.function.BiFunction
import java.util.regex.Matcher

import static liveplugin.PluginUtil.*

import java.util.regex.Pattern

import static com.intellij.openapi.util.text.StringUtil.newBombedCharSequence

// https://github.com/dkandalov/live-plugin/blob/master/src/plugin-util-groovy/liveplugin/PluginUtil.groovy



registerFunction("CUSTOM_DEBUG_LOG", new BiFunction<String, Matcher, String>() {
    Pattern pattern = Pattern.compile(".*\\[CUSTOM_DEBUG_LOG\\](.*)")

    // - The text will never be empty, it may or may not end with a newline - \n
    //  - It is possible that the stream will flush prematurely and the text will be incomplete: IDEA-70016
    //  - Return null to remove the line
    //  - Processing blocks application output stream, make sure to limit the length and processing time when needed using #limitAndCutNewline
    //
    @Override
    String apply(String text, Matcher matcher) {
        try {
            Matcher matched = pattern.matcher(text); // Apply the pattern
            // show("Matching: " + text)
            if (matched.find()) { // Use find() instead of matches()
                // show("Matched: " + text)
                if (matched.groupCount() >= 1) { // Ensure group 1 exists
                    return matched.group(1)+ "\n"; // Return group 1
                } else {
                    // show("No matching group found in: " + text);
                    return null;
                }
            } else {
                // show("No match for: " + text);
                return null;
            }
       } catch (com.intellij.openapi.progress.ProcessCanceledException ex) {
            show("Processing took too long for: " + text)
       }
       return text;
   }
})


static Class<?> getExtensionManager() {
    IdeaPluginDescriptorImpl descriptor = PluginManager.getPlugin(PluginId.getId("GrepConsole"))
    return descriptor.getPluginClassLoader().loadClass("krasa.grepconsole.plugin.ExtensionManager");
}

static void registerFunction(String functionName, Object function) {
    Class<?> clazz = getExtensionManager()

    clazz.getMethod("register", String.class, Object.class)
            .invoke(null, functionName, function);

    liveplugin.PluginUtil.show("'" + functionName + "' registered")
}
*/

function debug_log(string $text): void
{
    error_log("[CUSTOM_DEBUG_LOG]" . $text);
    xdebug_notify($text);
}

function debug_dump(string $text, mixed $variable)
{
    error_log("[CUSTOM_DEBUG_LOG] " . $text . " " . var_export($variable, true));
    xdebug_notify($text . " " . var_export($variable, true));
}