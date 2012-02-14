package IfTheHTTPReferrer::Tags;

sub hdlr_ifthehttpreferrer {
    my ( $ctx, $args, $cond ) = @_;
    my $referer = $ENV{ 'HTTP_REFERER' };
    my $scope = $args->{ scope };
    if (! defined( $scope ) ) {
        $scope = 'equal';
    }
    if ( $scope eq 'empty' ) {
        if (! $referer ) { return 1; }
    }
    if (! $referer ) { return 0; }
    if ( $referer =~ m!^https{0,1}://(.*?)/! ) {
        my $referer_domain = $1;
        my $domain = $args->{ domain };
        if ( $scope eq 'equal' ) {
            if ( $referer_domain eq $domain ) {
                return 1;
            }
        } else {
            $referer_domain = quotemeta( referer_domain );
            if ( $scope eq 'backward' ) {
                if ( $domain =~ m/$referer_domain$/ ) {
                    return 1;
                }
            } elsif ( $scope eq 'forward' ) {
                if ( $domain =~ m/^$referer_domain/ ) {
                    return 1;
                }
            } elsif ( $scope eq 'contains' ) {
                if ( $domain =~ m/$referer_domain/ ) {
                    return 1;
                }
            }
        }
        return 0;
    }
}

1;