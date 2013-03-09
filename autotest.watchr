watch("Tests/.*") { |md| 
	code_changed(md[0])
}
 
def code_changed(file)
	`clear`
    run "phpunit #{file}"
end
 
def run(cmd)
    result = `#{cmd}`
    growl result
end
 
def growl(message)
    puts(message)
    message = message.split("\n").last(3);
    growlnotify = `which growlnotify`.chomp

    empty_test = message.find { |e| /No tests executed/ =~ e }

    unless empty_test
 
    title = message.find { |e| /FAILURES/ =~ e } ? "FAILURES" : "PASS"
	    if title == "FAILURES"
	        image = "/Users/Technoguru/Pictures/GrowlNotification/fail.png"
	        info = "Tests have failed!"
	    else
	        image = "/Users/Technoguru/Pictures/GrowlNotification/pass.png"
	        info = "Your the best!!!"
	    end
	 
	    options = "-w -n Watchr --image '#{File.expand_path(image)}' --html '#{title}'  -m '#{info}'"
	    puts "#{growlnotify} #{options} &"
	    
	    system %(#{growlnotify} #{options} &)
	end
end