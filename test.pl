use Bio::EnsEMBL::Registry;
use Bio::SeqIO;

$ENV{MYSQL_UNIX_PORT} = "/Applications/MAMP/tmp/mysql/mysql.sock";

# Ouverture de la liste des gènes codant Ensembl : Gene_ens_75
unless ( open(file_list, $ARGV[0]) ) {
    print STDERR "Impossible de trouver $ARGV[0] ...\n\n";
    exit;
}

my %gene_list;

foreach my$line (<file_list>) {
	if ( $line =~ /^\s*$/ ){
    	next;
    }
    else {
    	my @line_content = split(" ", $line);
    	$gene_list{$line_content[0]}++;
    }
}
close file_list;

unless ( open(file_out, ">".$ARGV[1]) ) {
    print STDERR "Impossible de trouver $ARGV[1] ...\n\n";
    exit;
}

my $r = "Bio::EnsEMBL::Registry";
$r->load_registry_from_db(-host => "localhost", -user => "root", -pass => "root", -port => "8889", -verbose => "0");
# On utilise gene_adaptor pour récupérer les informations sur nos gènes
my $gene_adaptor=$r->get_adaptor("Human" ,"core", "Gene");

print file_out "INSERT INTO `gestion_prescription`.`gene` (`gene_id`, `gene_nom`, `gene_chromosome`)\nVALUES ";
foreach my $id(keys %gene_list) {
	my $gene = $gene_adaptor->fetch_by_stable_id($id);

	my $name = $gene->external_name();
	my $seq_region = $gene->seq_region_name();

	print file_out "(NULL, '$name', '$seq_region'),\n";
}

close file_out;
