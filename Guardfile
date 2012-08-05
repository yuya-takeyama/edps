# A sample Guardfile
# More info at https://github.com/guard/guard#readme

guard 'phpunit', :cli => '--colors', :tests_path => 'tests' do
  watch(%r{^src/([^/]+)/(.*)\.php$}) {|m| "tests/#{m[1]}/Tests/#{m[2]}Test.php" }
  watch(%r{^.+Test\.php$})
end
