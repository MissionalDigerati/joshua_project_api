watch("Tests/.*") { |md| 
	code_changed(md[0]) unless md[0].to_s.include?("Support")
}

watch("App/Includes/.*") { |md|
	file = File.join("Tests", "Unit", "#{File.basename(md[0], '.*')}Test.php")
	code_changed(file)
}

watch("App/Resources/.*") { |md|
	file = File.join("Tests", "Integration", "#{File.basename(md[0], '.*')}Test.php")
	code_changed(file)
}

def code_changed(file)
	system "clear"
    run("phpunit #{file}", file)
end
 
def run(cmd, file)
    result = `#{cmd}`
    growl(result, file)
end
 
def growl(message, file)
	puts file
    puts message
    message = message.split("\n").last(3);
    growlnotify = `which growlnotify`.chomp
	title = message.find { |e| /FAILURES/ =~ e } ? "FAILURES" : "PASS"
    if title == "FAILURES"
        image = "/Users/Technoguru/Pictures/GrowlNotification/fail.png"
        info = "Tests have failed on #{File.basename(file)}!"
    else
        image = "/Users/Technoguru/Pictures/GrowlNotification/pass.png"
        info = "Your the best!!! Test passed on #{File.basename(file)}!"
    end
 
    options = "-w -n Watchr --image '#{File.expand_path(image)}' --html '#{title}'  -m '#{info}'"
    system %(#{growlnotify} #{options} &)
end